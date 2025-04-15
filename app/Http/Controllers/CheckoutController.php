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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Item;


class CheckoutController extends Controller
{


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
        // Resposta para teste de conexão
        if ($request->isMethod('get')) {
            return response()->json(['status' => 'webhook ready'], 200);
        }

        $signature = $request->header('x-signature');
        if ($signature) {
            $generated = hash_hmac('sha256', $request->getContent(), config('services.mercadopago.webhook_secret'));
            if ($signature !== 'sha256='.$generated) {
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        try {
            $paymentId = $request->input('data.id');

            if (!$paymentId) {
                throw new Exception('Payment ID not found');
            }

            $payment = \MercadoPago\Payment::find_by_id($paymentId);
            $pedido = Pedidos::where('payment_id', $paymentId)->first();

            if (!$pedido) {
                return response()->json(['error' => 'Order not found'], 404);
            }

            $statusMap = [
                'approved' => 'Pagamento Aprovado',
                'pending' => 'Pagamento Pendente',
                'rejected' => 'Pagamento Recusado'
            ];

            $statusDesc = $statusMap[$payment->status] ?? 'Pagamento Pendente';
            $statusPedido = StatusPedidos::where('desc', $statusDesc)->first();

            if ($statusPedido) {
                $pedido->update(['status_pedido_id' => $statusPedido->id]);

                if ($pedido->user) {
                    Mail::to($pedido->user->email)
                       ->send(new StatusUpdatedMail($pedido, $statusDesc));
                }
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Webhook error: '.$e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
