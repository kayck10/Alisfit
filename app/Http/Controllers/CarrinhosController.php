<?php

namespace App\Http\Controllers;

use App\Models\CarrinhoIten;
use App\Models\Carrinhos;
use App\Models\Cupons;
use App\Models\Pedidos;
use App\Models\Produtos;
use Brian2694\Toastr\Facades\Toastr as FacadesToastr;
use Brian2694\Toastr\Toastr;
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
        FacadesToastr::success('Produto adicionado a sacola.', 'Sucesso', ["positionClass" => "toast-top-center"]);
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
        $carrinho = Carrinhos::where('user_id', Auth::id())->with('produtos.imagens', 'cupons')->first();

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

        return view('carrinho.info', compact('carrinho', 'subtotal', 'desconto', 'total'));
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
            // dd('oias');
            foreach ($carrinho->produtos as $produto) {
                if ($pedido && $pedido->exists) {
                    CarrinhoIten::where('carrinho_id', $carrinho->id)
                        ->where('produto_id', $produto->id)
                        ->update(['pedido_id' => $pedido->id]);
                }
            }

            // Processar pagamento no Mercado Pago
            SDK::setAccessToken(config('services.mercadopago.access_token'));
            $preference = new Preference();

            // 1. Adiciona os itens do carrinho
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
