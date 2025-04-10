<?php

namespace App\Http\Controllers;

use App\Models\CarrinhoIten;
use App\Models\Carrinhos;
use App\Models\Cupons;
use App\Models\Pedidos;
use App\Models\Produtos;
use Brian2694\Toastr\Facades\Toastr as FacadesToastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MercadoPago\Item;
use MercadoPago\Preference;
use MercadoPago\SDK;
use App\Services\FreteService;

class CarrinhosController extends Controller
{
    protected $freteService;

    public function __construct(FreteService $freteService)
    {
        $this->freteService = $freteService;
    }

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
            FacadesToastr::error('Você precisa estar logado para adicionar produtos a sua sacola.', 'Falha', ["positionClass" => "toast-top-center"]);
            return redirect()->back()->with('error', 'Faça login para adicionar produtos à sacola.');
        }

        // Validação sem obrigar a cor
        $request->validate([
            'tamanho_id' => 'required|exists:tamanhos,id',
            'quantidade' => 'required|integer|min:1'
        ], [
            'tamanho_id.required' => 'Por favor, selecione um tamanho antes de adicionar ao carrinho.',
        ]);

        $carrinho = Carrinhos::firstOrCreate(['user_id' => Auth::id()]);
        $produto = Produtos::find($produtoId);

        if (!$produto) {
            return redirect()->back()->with('error', 'Produto não encontrado!');
        }

        $quantidade = max(1, (int) $request->quantidade);

        $cor = $request->has('cor') ? $request->cor : null;

        $carrinhoItem = CarrinhoIten::where('carrinho_id', $carrinho->id)
            ->where('produto_id', $produtoId)
            ->where('tamanho_id', $request->tamanho_id)
            ->where(function ($query) use ($cor) {
                if ($cor === null) {
                    $query->whereNull('cor');
                } else {
                    $query->where('cor', $cor);
                }
            })
            ->first();

        if ($carrinhoItem) {
            $carrinhoItem->increment('quantidade', $quantidade);
        } else {
            CarrinhoIten::create([
                'carrinho_id' => $carrinho->id,
                'produto_id' => $produtoId,
                'quantidade' => $quantidade,
                'tamanho_id' => $request->tamanho_id,
                'cor' => $cor
            ]);
        }

        FacadesToastr::success('Produto adicionado à sacola.', 'Sucesso', ["positionClass" => "toast-top-center"]);
        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }


    public function atualizarQuantidade(Request $request, $produtoId)
    {

        Log::info('Requisição para atualizar carrinho', [
            'user_id' => Auth::id(),
            'produtoId' => $produtoId,
            'quantidade' => $request->quantidade
        ]);
        $carrinho = Carrinhos::where('user_id', Auth::id())->first();

        if ($carrinho) {
            $quantidade = max(1, (int) $request->quantidade); // Garante que a quantidade seja no mínimo 1
            $carrinho->produtos()->updateExistingPivot($produtoId, ['quantidade' => $quantidade]);

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
            FacadesToastr::success('Produto removido da sacola', 'Sucesso', ["positionClass" => "toast-top-center"]);
            return redirect()->back()->with('success', 'Produto removido do carrinho!');
        }

        return redirect()->back()->with('error', 'Carrinho não encontrado!');
    }

    public function info()
    {
        $produtos = Produtos::with([ 'tamanhos'])->get();
        $carrinho = Carrinhos::where('user_id', Auth::id())->with('produtos.imagens', 'cupons')->first();

        $cor_map = [
            'preto' => '#000000',
            'branco' => '#FFFFFF',
            'branca' => '#F5F5F5',
            'azul' => '#0000FF',
            'vermelho' => '#FF0000',
            'verde' => '#00FF00',
            'amarelo' => '#FFFF00',
            'roxo' => '#800080',
            'verde militar' => '#5d5f3d',
            'verde escuro' => '#006400',
            'verde floresta' => '#228B22',
            'verde lima' => '#32CD32',
            'verde mar' => '#2E8B57',
            'verde menta' => '#98FF98',
            'azul marinho' => '#000080',
            'azul claro' => '#ADD8E6',
            'azul celeste' => '#87CEEB',
            'azul aco' => '#4682B4',
            'azul petroleo' => '#008080',
            'azul real' => '#4169E1',
            'vermelho escuro' => '#8B0000',
            'rosa' => '#FFC0CB',
            'rosa choque' => '#FF69B4',
            'vinho' => '#722F37',
            'carmim' => '#960018',
            'salmao' => '#FA8072',
            'laranja' => '#FFA500',
            'laranja escuro' => '#FF8C00',
            'dourado' => '#FFD700',
            'amarelo_creme' => '#FFFDD0',
            'ambar' => '#FFBF00',
            'mostarda' => '#FFDB58',
            'lilas' => '#C8A2C8',
            'lavanda' => '#E6E6FA',
            'roxo_escuro' => '#301934',
            'ametista' => '#9966CC',
            'magenta' => '#FF00FF',
            'orquidea' => '#DA70D6',
            'marrom' => '#A52A2A',
            'bege' => '#F5F5DC',
            'cinza' => '#808080',
            'cinza_escuro' => '#A9A9A9',
            'cinza_claro' => '#D3D3D3',
            'terracota' => '#E2725B',
            'caramelo' => '#AF6E4D',
            'chocolate' => '#7B3F00',
            'turquesa' => '#40E0D0',
            'ciano' => '#00FFFF',
            'indigo' => '#4B0082',
            'prata' => '#C0C0C0',
            'bronze' => '#CD7F32',
            'oliva' => '#808000',
            'verde_azulado' => '#008080',
            'café' => '#6f4e37'
        ];

        if (!$carrinho) {
            return view('carrinho.info')->with('mensagem', 'Seu carrinho está vazio.');
        }

        $quantidadeProdutos = $carrinho->produtos()->count();
        $cupomAplicado = $carrinho->cupons()->exists();

        if ($cupomAplicado) {
            $carrinho->cupons()->detach();
        }

        $subtotal = $carrinho->produtos->sum(function ($produto) {
            return $produto->pivot->quantidade * $produto->preco;
        });

        $desconto = $carrinho->cupons->sum('pivot.desconto_aplicado');

        $total = max($subtotal - $desconto, 0);

        return view('carrinho.info', compact('carrinho', 'subtotal', 'desconto', 'total', 'cor_map', 'produtos'));
    }



    public function finalizar()
    {
        $carrinho = Carrinhos::with('produtos', 'cupons')->where('user_id', Auth::id())->first();

        if (!$carrinho || $carrinho->produtos->isEmpty()) {
            return redirect()->route('carrinho')->with('error', 'Carrinho vazio!');
        }

        $desconto = (float) $carrinho->cupons->sum('pivot.desconto_aplicado');

        $subtotal = $carrinho->produtos->sum(function ($produto) {
            return (float) ($produto->pivot->quantidade * $produto->preco);
        });

        $valorFrete = (float) session('valorFrete', 0);

        $total = max(0, ($subtotal + $valorFrete) - $desconto);
        return view('carrinho.finaliza', compact('carrinho', 'desconto', 'total'));
    }

    public function finalizarPedido(Request $request)
    {
        DB::beginTransaction();
        try {

            $frete = $this->calcularFrete($request->cep)['valor'];

            $carrinho = Carrinhos::with('produtos', 'cupons')->where('user_id', Auth::id())->first();

            if (!$carrinho) {
                return response()->json(['message' => 'Carrinho vazio!'], 400);
            }

            // Calcula totais
            $subtotal = $carrinho->produtos->sum(function ($produto) {
                return $produto->preco * $produto->pivot->quantidade;
            });

            $desconto = $carrinho->cupons->sum('pivot.desconto_aplicado');
            $valorFrete = floatval($frete);
            $totalPedido = ($subtotal + $valorFrete) - $desconto;

            // Criar pedido
            $pedido = Pedidos::create([
                'user_id' => Auth::id(),
                'carrinho_id' => $carrinho->id,
                'total' => $totalPedido,
                'status_pedido_id' => 1, // Aguardando pagamento
                'valor_frete' => $valorFrete,
                'desconto' => $desconto,
                'rua' => $request->rua,
                'numero' => $request->numero,
                'cidade' => $request->cidade,
                'estado' => $request->estado,
                'bairro' => $request->bairro,
                'cep' => $request->cep,
                'complemento' => $request->complemento,
            ]);

            // Vincular produtos ao pedido
            foreach ($carrinho->produtos as $produto) {
                // Certifique-se de que o pedido realmente existe no banco de dados
                if ($pedido && $pedido->exists) {
                    // Verifica se o item do carrinho realmente existe
                    $itemCarrinho = CarrinhoIten::where('carrinho_id', $carrinho->id)
                        ->where('produto_id', $produto->id)
                        ->first();

                    if ($itemCarrinho) {
                        $itemCarrinho->update(['pedido_id' => $pedido->id]);
                    } else {
                        \Log::warning("Item do carrinho não encontrado", [
                            'carrinho_id' => $carrinho->id,
                            'produto_id' => $produto->id
                        ]);
                    }
                } else {
                    \Log::error("Pedido não encontrado no banco de dados.", [
                        'pedido_id' => $pedido->id ?? 'null'
                    ]);
                }
            }


            SDK::setAccessToken(config('services.mercadopago.access_token'));
            $preference = new Preference();

            $items = [];
            foreach ($carrinho->produtos as $produto) {
                $item = new Item();
                $item->title = $produto->nome;
                $item->quantity = $produto->pivot->quantidade;
                $item->unit_price = $produto->preco;
                $item->currency_id = "BRL";
                $items[] = $item;
            }

            // 2. Adiciona o frete como um item separado
            if ($valorFrete > 0) {
                $freteItem = new Item();
                $freteItem->title = "Frete";
                $freteItem->quantity = 1;
                $freteItem->unit_price = $valorFrete;
                $freteItem->currency_id = "BRL";
                $items[] = $freteItem;
            }

            $preference->items = $items;

            // 3. Aplica desconto (se houver)
            if ($desconto > 0) {
                $preference->deductions = [
                    [
                        'type' => 'discount',
                        'value' => $desconto
                    ]
                ];
            }

            $preference->back_urls = [
                "success" => route('checkout.success', ['pedido_id' => $pedido->id]),
                "failure" => route('checkout.failure', ['pedido_id' => $pedido->id]),
                "pending" => route('checkout.pending', ['pedido_id' => $pedido->id]),
            ];
            $preference->auto_return = "approved";
            $preference->external_reference = $pedido->id; // Importante para vincular ao pedido
            $preference->save();

            DB::commit();
            return response()->json([
                'redirect' => $preference->init_point
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao processar pedido: ' . $e->getMessage()], 500);
        }
    }

    public function calcularFrete($cep)
    {
        $cepDestino = $cep;
        $frete = $this->freteService->calcularFrete($cepDestino);

        if (isset($frete['error'])) {
            return redirect()->back()->with('error', $frete['error']);
        }
        return $frete;
    }
}
