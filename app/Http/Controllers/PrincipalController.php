<?php

namespace App\Http\Controllers;

use App\Models\Carrinhos;
use App\Models\Colecoes;
use App\Models\Generos;
use App\Models\Pedidos;
use App\Models\Produtos;
use App\Models\ProdutosTamanhos;
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


    public function sobre()
    {
        return view('principal.sobre');
    }



    public function produtos()
    {
        $tamanhos = Tamanhos::all();
        $produtos = Produtos::with(['colecao', 'tamanhos', 'imagens'])->get();
        $colecoes = Colecoes::all();
        return view('principal.produtos', compact('tamanhos', 'produtos', 'colecoes'));
    }

    public function drops(Request $request)
    {
        $tamanhos = Tamanhos::all();
        $colecoes = Colecoes::all();
        $generos = Generos::all();

        $cores = ProdutosTamanhos::distinct()->pluck('cor')->filter()->values();

        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        $produtos = Produtos::with(['colecao', 'tamanhos', 'imagens', 'genero']);

        if ($request->has('colecao_id')) {
            $produtos->whereIn('colecao_id', $request->colecao_id);
        }

        if ($request->has('tamanho_id')) {
            $produtos->whereHas('tamanhos', function ($query) use ($request) {
                $query->whereIn('tamanho_id', $request->tamanho_id);
            });
        }

        if ($request->has('genero_id')) {
            $produtos->whereIn('genero_id', $request->genero_id);
        }

        if ($request->has('cor')) {
            $produtos->whereHas('tamanhos', function ($query) use ($request) {
                $query->whereIn('cor', $request->cor);
            });
        }

        $produtos = $produtos->get();

        if ($request->wantsJson()) {
            return response()->json(['produtos' => $produtos]);
        }

        return view('drops.index', compact('tamanhos', 'produtos', 'colecoes', 'generos', 'cores', 'carrinho'));
    }

    public function filtrarDrops(Request $request)
    {
        $filtros = $request->only(['colecao_id', 'tamanho_id', 'genero_id', 'cor']);

        $produtos = Produtos::with(['colecao', 'tamanhos', 'imagens', 'genero']);

        if (!empty($filtros['colecao_id'])) {
            $produtos->whereIn('colecao_id', $filtros['colecao_id']);
        }

        if (!empty($filtros['tamanho_id'])) {
            $produtos->whereHas('tamanhos', function ($query) use ($filtros) {
                $query->whereIn('tamanho_id', $filtros['tamanho_id']);
            });
        }

        if (!empty($filtros['genero_id'])) {
            $produtos->whereIn('genero_id', $filtros['genero_id']);
        }

        if (!empty($filtros['cor'])) {
            $produtos->whereHas('tamanhos', function ($query) use ($filtros) {
                $query->whereIn('cor', $filtros['cor']);
            });
        }

        $produtos = $produtos->get();

        return response()->json(['produtos' => $produtos]);
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

    public function informacoes()
    {
        return view('principal.informacoes');
    }

    public function conta()
    {
        return view('principal.conta');
    }

    public function carregarPagina($page)
    {
        $pages = [
            'visao-geral' => 'conta.visao-geral',
            'meus-pedidos' => 'conta.meus-pedidos',
            'dados-pessoais' => 'conta.dados-pessoais',
            'enderecos' => 'conta.enderecos',
            'lista-desejos' => 'conta.lista-desejos',
        ];

        if (!array_key_exists($page, $pages)) {
            return response()->json(['error' => 'Página não encontrada'], 404);
        }

        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Usuário não autenticado'], 401);
        }

        if ($page === 'meus-pedidos') {
            $pedidos = Pedidos::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            return view($pages[$page], compact('pedidos'));
        }

        if ($page === 'dados-pessoais') {
            return view($pages[$page], compact('user'));
        }

        return view($pages[$page]);
    }
}
