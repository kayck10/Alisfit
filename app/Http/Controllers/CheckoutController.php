<?php

namespace App\Http\Controllers;

use App\Mail\StatusUpdatedMail;
use App\Models\Carrinhos;
use App\Models\Cupons;
use App\Models\Pedidos;
use App\Models\StatusPedidos;
use Exception;
use Faker\Provider\pt_BR\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;


class CheckoutController extends Controller
{

    public function processarCheckout(Request $request)
    {
        try {

            $desconto = 0;

            if ($request->codigoCupom) {
                $this->aplicarCupomAoPedido($request->pedidoId, $request->codigoCupom, $request->valor_total);
                $desconto = Cupons::whereCodigo($request->codigoCupom)->first()->valor;
            }
            SDK::setAccessToken(config('services.mercadopago.access_token'));

            $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

            if (!$carrinho || $carrinho->produtos->isEmpty()) {
                return redirect()->back()->with('error', 'Seu carrinho está vazio!');
            }

            $preference = new Preference();
            $items = [];

            $teste = 0;
            foreach ($carrinho->produtos as $produto) {
                $item = new Item();
                $item->title = $produto->nome;
                $item->quantity = $produto->pivot->quantidade;
                $aplicarDesconto = ($produto->preco * $desconto) / 100;
                $teste += ($produto->preco - $aplicarDesconto);
                $item->unit_price = ((float) $produto->preco - $aplicarDesconto);
                $item->currency_id = "BRL";
                $items[] = $item;
            }

            $preference->items = $items;
            $preference->back_urls = [
                "success" => route('checkout.success', ['pedido_id' => $request->pedidoId]),
                "failure" => route('checkout.failure', ['pedido_id' => $request->pedidoId]),
                "pending" => route('checkout.pending', ['pedido_id' => $request->pedidoId]),
            ];
            $preference->auto_return = "approved";

            $preference->save();

            if (!empty($preference->init_point)) {
                return redirect()->away($preference->init_point);
            } else {
                return redirect()->route('carrinho')->with('error', 'Ocorreu um erro ao processar o pagamento.');
            }
        } catch (Exception $e) {
            return "aconteceu um erro: $e";
        }
    }

    public function aplicarCupom(Request $request)
    {
        $codigoCupom = $request->codigoCupom;
        $cupom = Cupons::where('codigo', $codigoCupom)->where('ativo', true)->first();

        if (!$cupom) {
            return response()->json(['error' => true, 'msg' => 'Cupom inválido ou expirado.']);
        }

        $carrinho = session()->get('carrinho', []);
        $subtotal = collect($carrinho)->sum(function ($produto) {
            return $produto['quantidade'] * $produto['preco'];
        });

        if ($cupom->tipo === 'percentual') {
            $desconto = ($subtotal * $cupom->valor) / 100;
        } elseif ($cupom->tipo === 'fixo') {
            $desconto = min($cupom->valor, $subtotal);
        }

        $novoTotal = $subtotal - $desconto;

        session(['desconto' => $desconto, 'cupom' => $codigoCupom]);

        return response()->json([
            'error' => false,
            'novoTotal' => $novoTotal,
        ]);
    }


    public function success(Request $request)
    {

        $pedido_id = $request->pedido_id;
        $paymentId = $request->get('payment_id');

        if (!$paymentId) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Payment ID não encontrado!');
        }

        $pedido = Pedidos::find($pedido_id);

        $pedido->update([
            'payment_id' => $paymentId,
        ]);

        if ($pedido->carrinho) {

            $pedido->carrinho->produtos()->detach();
            $pedido->carrinho->delete();
        }

        if (!$pedido) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Pedido não encontrado!');
        }

        $statusPedido = $pedido->status;

        if (!$statusPedido) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Status do pagamento não encontrado!');
        }

        $pedido->update([
            'status_pedido_id' => 2,

        ]);
        if ($statusPedido->desc == 'Pagamento Aprovado' && $pedido->user) {
            $mail = Mail::to($pedido->user->email)->send(new StatusUpdatedMail($pedido, $statusPedido->desc));
        }


        return view('checkout.success', ['payment' => $statusPedido, 'pedido' => $pedido]);
    }


    public function webhook(Request $request)
    {
        SDK::setAccessToken(config('services.mercadopago.access_token'));

        $paymentId = $request->input('data.id');

        $pedido = Pedidos::where('payment_id', $paymentId)->first();

        if (!$pedido) {
            return response()->json(['status' => 'error', 'message' => 'Pedido não encontrado.'], 404);
        }

        $statusPedido = $pedido->status;

        if (!$statusPedido) {
            return response()->json(['status' => 'error', 'message' => 'Status do pagamento não encontrado.'], 404);
        }

        switch ($request->input('data.status')) {
            case 'approved':
                $statusPedido = StatusPedidos::where('desc', 'Pagamento Aprovado')->first();
                break;
            case 'pending':
                $statusPedido = StatusPedidos::where('desc', 'Pagamento Pendente')->first();
                break;
            case 'rejected':
                $statusPedido = StatusPedidos::where('desc', 'Pagamento Recusado')->first();
                break;
            default:
                $statusPedido = StatusPedidos::where('desc', 'Pagamento Pendente')->first();
                break;
        }

        if ($statusPedido) {
            $pedido->update([
                'status_pedido_id' => $statusPedido->id,
            ]);

            if ($statusPedido->desc == 'Pagamento Aprovado' && $pedido->user) {
                Mail::to($pedido->user->email)->send(new StatusUpdatedMail($pedido, $statusPedido->desc));
            } elseif ($statusPedido->desc == 'Pagamento Pendente' && $pedido->user) {
                Mail::to($pedido->user->email)->send(new StatusUpdatedMail($pedido, $statusPedido->desc));
            } elseif ($statusPedido->desc == 'Pagamento Recusado' && $pedido->user) {
                Mail::to($pedido->user->email)->send(new StatusUpdatedMail($pedido, $statusPedido->desc));
            }
        }

        return response()->json(['status' => 'success', 'message' => 'Status do pedido atualizado com sucesso!']);
    }


    public function failure(Request $request)
    {
        $pedido_id = $request->pedido_id;
        $paymentId = $request->get('payment_id');

        if (!$paymentId) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Payment ID não encontrado!');
        }

        $pedido = Pedidos::find($pedido_id);

        $pedido->update([
            'payment_id' => $paymentId,
        ]);

        if (!$pedido) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Pedido não encontrado!');
        }

        $statusPedido = $pedido->status;

        if (!$statusPedido) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Status do pagamento não encontrado!');
        }

        $pedido->update([
            'status_pedido_id' => 3,
        ]);

        if ($statusPedido->desc == 'Pagamento Recusado' && $pedido->user) {
            Mail::to($pedido->user->email)->send(new StatusUpdatedMail($pedido, $statusPedido->desc));
        }

        return view('checkout.failure', ['payment' => $statusPedido, 'pedido' => $pedido]);
    }

    public function pending(Request $request)
    {
        $pedido_id = $request->pedido_id;
        $paymentId = $request->get('payment_id');

        if (!$paymentId) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Payment ID não encontrado!');
        }

        $pedido = Pedidos::find($pedido_id);

        $pedido->update([
            'payment_id' => $paymentId,
        ]);

        if (!$pedido) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Pedido não encontrado!');
        }

        $statusPedido = $pedido->status;

        if (!$statusPedido) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Status do pagamento não encontrado!');
        }

        $pedido->update([
            'status_pedido_id' => 1,
        ]);

        if ($statusPedido->desc == 'Pagamento Pendente' && $pedido->user) {
            Mail::to($pedido->user->email)->send(new StatusUpdatedMail($pedido, $statusPedido->desc));
        }

        return view('checkout.pending', ['payment' => $statusPedido, 'pedido' => $pedido]);
    }
}
