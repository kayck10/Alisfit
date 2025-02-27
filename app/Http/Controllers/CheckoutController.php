<?php

namespace App\Http\Controllers;

use App\Models\Carrinhos;
use App\Models\Cupons;
use App\Models\Pedidos;
use App\Models\StatusPedidos;
use Faker\Provider\pt_BR\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;


class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
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
            "success" => route('checkout.success'),
            "failure" => route('checkout.failure'),
            "pending" => route('checkout.pending'),
        ];
        $preference->auto_return = "approved";

        $preference->save();




        return redirect($preference->init_point);
    }

    private function aplicarCupomAoPedido($pedidoId, $codigo, $valorTotal)
    {

        $pedido = Pedidos::with('cupons')->findOrFail($pedidoId);
        $cupom = Cupons::where('codigo', $codigo)->first();


        if ($cupom->tipo === 'percentual') {
            $desconto = ($pedido->total * $cupom->valor) / 100;
        } elseif ($cupom->tipo === 'fixo') {
            $desconto = min($cupom->valor, $pedido->total);
        }

        $pedido->cupons()->attach($cupom->id, [
            'desconto_aplicado' => $desconto,
        ]);

        $pedido->update([
            'total' => $valorTotal,
        ]);

        if ($cupom->quantidade > 0) {
            $cupom->decrement('quantidade');
        }

        $pedido->update(['valor_total' => $valorTotal]);
        return $desconto;
    }

    public function success(Request $request)
    {
        // Obtém o payment_id da requisição
        $paymentId = $request->get('payment_id');

        // Verifica se o payment_id foi passado e busca o pedido correspondente
        if (!$paymentId) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Payment ID não encontrado!');
        }

        // Busca o pedido relacionado ao payment_id
        $pedido = Pedidos::where('payment_id', $paymentId)->first();

        if (!$pedido) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Pedido não encontrado!');
        }

        // Acessa o status do pedido através do relacionamento
        $statusPedido = $pedido->status; // Aqui é o relacionamento que traz o StatusPedidos

        if (!$statusPedido) {
            return redirect()->route('carrinho.finalizar')->with('error', 'Status do pagamento não encontrado!');
        }

        // Define o statusId com base na descrição do status
        $statusId = 0;
        if ($statusPedido->desc == 'approved') {
            $statusId = 2; // Aprovado
        } elseif ($statusPedido->desc == 'pending') {
            $statusId = 1; // Pendente
        } elseif ($statusPedido->desc == 'rejected') {
            $statusId = 3; // Recusado
        }

        // Atualiza o pedido com o novo status
        $pedido->update([
            'status_pedido_id' => $statusId,
        ]);

        // Retorna a view com base no status do pagamento
        if ($statusPedido->desc == 'approved') {
            return view('checkout.success', ['payment' => $statusPedido, 'pedido' => $pedido]);
        } elseif ($statusPedido->desc == 'pending') {
            return view('checkout.pending', ['payment' => $statusPedido, 'pedido' => $pedido]);
        } else {
            return view('checkout.failure', ['payment' => $statusPedido, 'pedido' => $pedido]);
        }
    }





    public function webhook(Request $request)
    {
        SDK::setAccessToken(config('services.mercadopago.access_token'));

        // Obtém o payment_id da requisição
        $paymentId = $request->input('data.id');

        // Verifica se o payment_id foi passado e busca o pedido correspondente
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
        }

        return response()->json(['status' => 'success', 'message' => 'Status do pedido atualizado com sucesso!']);
    }


    public function failure()
    {
        return view('checkout.failure');
    }

    public function pending()
    {
        return view('checkout.pending');
    }
}
