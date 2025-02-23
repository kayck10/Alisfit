<?php

namespace App\Http\Controllers;

use App\Models\Cupons;
use App\Models\Pedidos;
use Illuminate\Http\Request;

class PedidosController extends Controller
{



    public function retornarDescontoCupom(Request $request)
    {
        $pedido = Pedidos::with('cupons')->findOrFail($request->pedidoId);

        $cupom = Cupons::where('codigo', $request->codigoCupom)->first();

        if(!$cupom || $cupom->quantidade <= 0) {
            return ['error' => true, 'msg' => "Cupom inválido", "results" => $pedido->total];
        }

        if ($cupom->tipo === 'percentual') {
            $desconto = ($pedido->total * $cupom->valor) / 100;
        } elseif ($cupom->tipo === 'fixo') {
            $desconto = min($cupom->valor, $pedido->total);
        }

        return max($pedido->total - $desconto, 0);

    }

}
