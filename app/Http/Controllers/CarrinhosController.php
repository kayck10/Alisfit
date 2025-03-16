<?php

namespace App\Http\Controllers;

use App\Models\CarrinhoIten;
use App\Models\Carrinhos;
use App\Models\Pedidos;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarrinhosController extends Controller
{
    public function carrinho()
    {

        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        if (!$carrinho) {
            $carrinho = Carrinhos::create(['user_id' => Auth::id()]);
        }

        if (!$carrinho->produtos) {
            $carrinho->produtos = collect();
        }

        return view('Layout.principal', compact('carrinho'));
    }

    public function adicionarProduto(Request $request, $produtoId)
    {
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Faça login para adicionar produtos à sacola.');
        }

        $request->validate([
            'tamanho_id' => 'required|exists:tamanhos,id',
            'cor' => 'required|string',
            'quantidade' => 'required|integer|min:1'
        ]);

        $carrinho = Carrinhos::firstOrCreate(['user_id' => Auth::id()]);
        $produto = Produtos::find($produtoId);

        if (!$produto) {
            return redirect()->back()->with('error', 'Produto não encontrado!');
        }

        $quantidade = max(1, (int) $request->quantidade);

        $carrinhoItem = CarrinhoIten::where('carrinho_id', $carrinho->id)
            ->where('produto_id', $produtoId)
            ->where('tamanho_id', $request->tamanho_id)
            ->where('cor', $request->cor)
            ->first();

        if ($carrinhoItem) {
            $carrinhoItem->increment('quantidade', $quantidade);
        } else {
            CarrinhoIten::create([
                'carrinho_id' => $carrinho->id,
                'produto_id' => $produtoId,
                'quantidade' => $quantidade,
                'tamanho_id' => $request->tamanho_id,
                'cor' => $request->cor
            ]);
        }

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }






    public function atualizarQuantidade(Request $request, $produtoId)
    {
        $carrinho = Carrinhos::where('user_id', Auth::id())->first();
        if ($carrinho) {
            $carrinho->produtos()->updateExistingPivot($produtoId, [
                'quantidade' => $request->quantidade
            ]);

            $novoCarrinho = $carrinho->fresh();

            return response()->json([
                'success' => true,
                'produtos' => $novoCarrinho->produtos,
                'total' => number_format($novoCarrinho->produtos->sum(function ($produto) {
                    return $produto->preco * $produto->pivot->quantidade;
                }), 2, ',', '.')
            ]);
        }

        return response()->json(['error' => 'Carrinho não encontrado!'], 404);
    }


    public function removerProduto($produtoId)
    {
        $carrinho = Carrinhos::where('user_id', Auth::id())->first();

        if ($carrinho) {
            $carrinho->produtos()->detach($produtoId);

            return redirect()->back()->with('success', 'Produto removido do carrinho!');
        }

        return redirect()->back()->with('error', 'Carrinho não encontrado!');
    }


    public function finalizar(Request $request)
    {
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        if (!$carrinho) {
            return redirect()->route('carrinho')->with('error', 'Carrinho vazio!');
        }

        $pedido = Pedidos::create([
            'user_id' => Auth::id(),
            'carrinho_id' => $carrinho->id,
            'total' => $carrinho->produtos->sum(function ($produto) {
                return $produto->preco * $produto->pivot->quantidade;
            }),
            'status' => 'pendente',
            'status_pedido_id' => 1,
            'rua' => $request->rua,
            'numero' => $request->numero,
            'cidade' => $request->cidade,
            'estado' => $request->estado,
            'cep' => $request->cep,

        ]);


        $pedido->load('cupons');

        return view('carrinho.finaliza', compact('carrinho', 'pedido'));
    }
}
