<?php

namespace App\Http\Controllers;

use App\Models\Carrinhos;
use App\Models\Colecoes;
use App\Models\Produtos;
use App\Models\Tamanhos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrincipalController extends Controller
{
    public function index()
    {
        $produtos = Produtos::with(['colecao', 'tamanhos', 'imagens'])->get();
        $colecoes = Colecoes::all();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        $pedido = optional($carrinho)->pedido_id;

        return view('principal.index', compact('produtos', 'colecoes', 'carrinho', 'pedido'));
    }


    public function sobre() {
        return view('principal.sobre');
    }



    public function produtos()
    {
        $tamanhos = Tamanhos::all();
        $produtos = Produtos::with(['colecao', 'tamanhos', 'imagens'])->get();
        $colecoes = Colecoes::all();
        return view('principal.produtos', compact('tamanhos', 'produtos', 'colecoes'));
    }
    public function drops()
    {
        $tamanhos = Tamanhos::all();
        $produtos = Produtos::with(['colecao', 'tamanhos', 'imagens'])->get();
        $colecoes = Colecoes::all();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();
        return view('drops.index', compact('tamanhos', 'produtos', 'colecoes', 'carrinho'));
    }

    public function produtoDetalhes($id)
    {
        $produto = Produtos::with('tamanhos', 'imagens')->findOrFail($id);

        $cor_map = [
            'preto' => '#000000',
            'branco' => '#FFFFFF',
            'azul' => '#0000FF',
            'vermelho' => '#FF0000',
            'verde' => '#00FF00',
            'amarelo' => '#FFFF00',
            'roxo' => '#800080',
        ];


        $produtosRelacionados = Produtos::where('colecao_id', $produto->colecao_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        return view('principal.produto_detalhes', compact('produto', 'produtosRelacionados', 'cor_map', 'carrinho'));
    }

    public function showCol(Colecoes $colecao)
    {

        $produtos = $colecao->produtos;
        return view('principal.colecoes', compact('colecao', 'produtos'));
    }


}
