<?php

namespace App\Http\Controllers;

use App\Mail\PedidoStatusAtualizado;
use App\Mail\StatusUpdatedMail;
use App\Models\Carrinhos;
use App\Models\Cupons;
use App\Models\Pedidos;
use App\Models\StatusPedidos;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PedidosController extends Controller
{

    public function index()
    {
        $pedidos = Pedidos::with(['user', 'status', 'carrinho'])->where('status_pedido_id', 2)->get();
        $status = StatusPedidos::all(); // Carrega todos os status

        return view('pedidos.index', compact('pedidos', 'status'));
    }


    public function show($id)
    {
        $pedido = Pedidos::with('user', 'status')->find($id);
        return response()->json($pedido);
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

    public function aplicarCupomCarrinho(Request $request)
    {
        Log::info('Dados da requisição para aplicar cupom:', $request->all());

        if (empty($request->codigoCupom)) {
            return response()->json(['error' => true, 'msg' => 'Código do cupom não fornecido.']);
        }

        $cupom = Cupons::where('codigo', $request->codigoCupom)
            ->where('ativo', true)
            ->first();

        if (!$cupom || !$cupom->isValido()) {
            Log::warning('Cupom inválido ou expirado:', ['codigo' => $request->codigoCupom]);
            return response()->json(['error' => true, 'msg' => 'Cupom inválido ou expirado.']);
        }


        $carrinho = Carrinhos::with('produtos')->find($request->carrinhoId);

        if ($carrinho->cupons()->where('cupom_id', $cupom->id)->exists()) {
            return response()->json(['error' => true, 'msg' => 'Este cupom já foi aplicado ao carrinho.']);
        }

        $subtotal = $carrinho->produtos->sum(function ($produto) {
            return $produto->pivot->quantidade * $produto->preco;
        });

        $desconto = ($cupom->tipo === 'percentual') ? ($subtotal * $cupom->valor) / 100 : min($cupom->valor, $subtotal);

        $carrinho->cupons()->attach($cupom->id, ['desconto_aplicado' => $desconto]);

        // dd($carrinho->cupons()->where('cupom_id', $cupom->id)->exists());

        $novoTotal = max($subtotal - $desconto, 0);

        Log::info('Cupom aplicado com sucesso no carrinho.', [
            'carrinho_id' => $carrinho->id,
            'cupom_id' => $cupom->id,
            'desconto_aplicado' => $desconto,
            'novo_total' => $novoTotal
        ]);
        Toastr::success('Cupom Aplicado', 'Sucesso', ["positionClass" => "toast-top-center"]);
        return redirect()->back()->with('success', 'Cupom aplicado com sucesso!');
    }
}
