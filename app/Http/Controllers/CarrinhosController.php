<?php

namespace App\Http\Controllers;

use App\Models\Carrinhos;
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
        $carrinho = Carrinhos::firstOrCreate(['user_id' => Auth::id()]);
        $produto = Produtos::find($produtoId);

        if ($produto) {
            $carrinhoItem = $carrinho->produtos()->where('produto_id', $produtoId)->first();

            if ($carrinhoItem) {
                $carrinho->produtos()->updateExistingPivot($produtoId, [
                    'quantidade' => $carrinhoItem->pivot->quantidade + $request->quantidade
                ]);
            } else {
                $carrinho->produtos()->attach($produtoId, [
                    'quantidade' => $request->quantidade
                ]);
            }

            return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
        }

        return redirect()->back()->with('error', 'Produto não encontrado!');
    }

    public function atualizarQuantidade(Request $request, $produtoId)

    {
        $carrinho = Carrinhos::where('user_id', Auth::id())->first();
        if ($carrinho) {
            $carrinho->produtos()->updateExistingPivot($produtoId, [
                'quantidade' => $request->quantidade
            ]);

            return redirect()->back()->with('success', 'Quantidade atualizada!');
        }

        return redirect()->back()->with('error', 'Carrinho não encontrado!');
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
}
