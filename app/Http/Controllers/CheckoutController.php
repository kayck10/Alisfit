<?php

namespace App\Http\Controllers;

use App\Models\Carrinhos;
use App\Models\Cupons;
use App\Models\Pedidos;
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

        if($request->codigoCupom) {
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

        // if (!$cupom || !$cupom->isValido()) {
        //     return redirect()->route('carrinho.finalizar', ['pedidoId' => $pedido->id])->with('error', 'Cupom inválido ou expirado.');
        // }

        // if ($pedido->cupons()->where('cupom_id', $cupom->id)->exists()) {
        //     return redirect()->route('carrinho.finalizar', ['pedidoId' => $pedido->id])->with('error', 'Este cupom já foi aplicado ao pedido.');
        // }
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

    public function success()
    {
        return view('checkout.success');
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
