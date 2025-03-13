<?php

namespace App\Http\Controllers;

use App\Mail\PedidoStatusAtualizado;
use App\Mail\StatusUpdatedMail;
use App\Models\Cupons;
use App\Models\Pedidos;
use App\Models\StatusPedidos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PedidosController extends Controller
{

    public function index()
    {
        $pedidos = Pedidos::with(['user', 'cupons', 'status', 'carrinho'])->paginate(10);
        $status = StatusPedidos::all();

        return view('pedidos.index', compact('pedidos', 'status'));
    }


    public function atualizarStatus(Request $request, $id)
{
    $pedido = Pedidos::findOrFail($id);

    $pedido->status_pedido_id = $request->status;
    $pedido->save();

    $statusAtual = $pedido->status;

    if ($pedido->user) {
        Mail::to($pedido->user->email)->send(new StatusUpdatedMail($pedido, $statusAtual->desc));
    } else {
        Log::error('Usuário não encontrado para o pedido ' . $pedido->id);
    }

    return redirect()->back()->with('success', 'Status atualizado com sucesso!');
}



    public function retornarDescontoCupom(Request $request)
    {
        $pedido = Pedidos::with('cupons')->findOrFail($request->pedidoId);

        $cupom = Cupons::where('codigo', $request->codigoCupom)->first();

        if (!$cupom || $cupom->quantidade <= 0) {
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
