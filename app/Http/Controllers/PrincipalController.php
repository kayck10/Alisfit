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
        $produtos = Produtos::with(['colecao', 'tamanhos', 'imagens'])
            ->where('destaque', true)
            ->take(4)
            ->get();
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
        $produto = Produtos::with('tamanhos', 'imagens', 'tipoProduto')->findOrFail($id);

        $cor_map = [
            // Cores básicas
            'preto' => '#000000',
            'branco' => '#FFFFFF',
            'azul' => '#0000FF',
            'vermelho' => '#FF0000',
            'verde' => '#00FF00',
            'amarelo' => '#FFFF00',
            'roxo' => '#800080',

            // Tons de verde
            'verde militar' => '#5d5f3d',
            'verde escuro' => '#006400',
            'verde floresta' => '#228B22',
            'verde lima' => '#32CD32',
            'verde mar' => '#2E8B57',
            'verde menta' => '#98FF98',

            // Tons de azul
            'azul marinho' => '#000080',
            'azul claro' => '#ADD8E6',
            'azul celeste' => '#87CEEB',
            'azul aco' => '#4682B4',
            'azul petroleo' => '#008080',
            'azul real' => '#4169E1',

            // Tons de vermelho/rosa
            'vermelho escuro' => '#8B0000',
            'rosa' => '#FFC0CB',
            'rosa choque' => '#FF69B4',
            'vinho' => '#722F37',
            'carmim' => '#960018',
            'salmao' => '#FA8072',

            // Tons de amarelo/laranja
            'laranja' => '#FFA500',
            'laranja escuro' => '#FF8C00',
            'dourado' => '#FFD700',
            'amarelo_creme' => '#FFFDD0',
            'ambar' => '#FFBF00',
            'mostarda' => '#FFDB58',

            // Tons de roxo/lilás
            'lilas' => '#C8A2C8',
            'lavanda' => '#E6E6FA',
            'roxo_escuro' => '#301934',
            'ametista' => '#9966CC',
            'magenta' => '#FF00FF',
            'orquidea' => '#DA70D6',

            // Tons terrosos/neutros
            'marrom' => '#A52A2A',
            'bege' => '#F5F5DC',
            'cinza' => '#808080',
            'cinza_escuro' => '#A9A9A9',
            'cinza_claro' => '#D3D3D3',
            'terracota' => '#E2725B',
            'caramelo' => '#AF6E4D',
            'chocolate' => '#7B3F00',

            // Cores especiais
            'turquesa' => '#40E0D0',
            'ciano' => '#00FFFF',
            'indigo' => '#4B0082',
            'prata' => '#C0C0C0',
            'bronze' => '#CD7F32',
            'oliva' => '#808000',
            'verde_azulado' => '#008080'
        ];


        // 1. Primeiro tenta encontrar produtos complementares
        $produtosRelacionados = $this->getProdutosComplementares($produto);

        // 2. Se não encontrou suficientes, completa com produtos da mesma coleção
        if ($produtosRelacionados->count() < 4) {
            $produtosAdicionais = Produtos::where('colecao_id', $produto->colecao_id)
                ->where('id', '!=', $id)
                ->whereNotIn('id', $produtosRelacionados->pluck('id'))
                ->inRandomOrder()
                ->take(4 - $produtosRelacionados->count())
                ->get();

            $produtosRelacionados = $produtosRelacionados->merge($produtosAdicionais);
        }

        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        return view('principal.produto_detalhes', compact('produto', 'produtosRelacionados', 'cor_map', 'carrinho'));
    }

    private function getProdutosComplementares($produto)
    {
        $tiposRelacionados = $this->getTiposRelacionados($produto->tipo_produto_id);

        return Produtos::where('colecao_id', $produto->colecao_id)
            ->where('id', '!=', $produto->id)
            ->whereIn('tipo_produto_id', $tiposRelacionados)
            ->whereHas('tamanhos', function ($query) {
                $query->where('quantidade', '>', 0);
            })
            ->inRandomOrder()
            ->take(4)
            ->get();
    }

    private function getTiposRelacionados($tipoProdutoId)
    {
        $mapeamento = [
            1 => [2, 3, 4], // Camisa (1) mostra shorts (2), leggings (3) e conjuntos (4)
            2 => [1, 4],    // Short (2) mostra camisas (1) e conjuntos (4)
            3 => [1, 5],    // Legging (3) mostra camisas (1) e tops (5)
            4 => [1, 2],    // Conjunto (4) mostra camisas (1) e shorts (2)
            5 => [3]        // Top (5) mostra leggings (3)
        ];

        return $mapeamento[$tipoProdutoId] ?? [1, 2, 3, 4, 5]; // Default para todos os tipos se não mapeado
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
