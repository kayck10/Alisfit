<?php

use App\Http\Controllers\CarrinhosController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ColecoesController;
use App\Http\Controllers\CuponsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FinanceiroController;
use App\Http\Controllers\FreteController;
use App\Http\Controllers\ImagensProdutoController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\TabelaMedidasController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Route::get('/login/admin', [LoginController::class, 'login'])->name('login');

Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/login/cliente', [LoginController::class, 'Clistore'])->name('cliente.store');
Route::post('/cadastro/cliente', [LoginController::class, 'cadastro'])->name('cliente.cadastro');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/', [PrincipalController::class, 'index'])->name('loja.create');

Route::prefix('loja')->group(function () {
    Route::get('/sobre', [PrincipalController::class, 'sobre'])->name('loja.sobre');
    Route::get('/teste', [PrincipalController::class, 'teste'])->name('teste');
    Route::post('/ajax-request', [PrincipalController::class, 'handleRequest'])->name('ajax.request');
    Route::get('produtos', [PrincipalController::class, 'produtos'])->name('produtos');
    Route::get('drops', [PrincipalController::class, 'drops'])->name('drops');
    Route::post('/drops/filtrar', [PrincipalController::class, 'filtrarDrops'])->name('drops.filtrar');
    Route::get('produto/{id}', [PrincipalController::class, 'produtoDetalhes'])->name('produto.detalhes');
    Route::get('/colecoes/{colecao}', [PrincipalController::class, 'showCol'])->name('colecoes.show.loja');
    Route::get('/informacoes', [PrincipalController::class, 'informacoes'])->name('informacoes');

    Route::get('/conta', [PrincipalController::class, 'conta'])->name('conta');
    Route::get('/conta/{page}', [PrincipalController::class, 'carregarPagina'])->name('conta.pagina');


    Route::group(['middleware' => 'cliente.auth'], function () {
        Route::prefix('carrinho')->group(function () {
            Route::get('/', [CarrinhosController::class, 'carrinho'])->name('carrinho');
            Route::post('/adicionar/{produtoId}', [CarrinhosController::class, 'adicionarProduto'])->name('carrinho.adicionar');
            Route::post('/atualizar/{produtoId}', [CarrinhosController::class, 'atualizarQuantidade'])->name('carrinho.atualizar');
            Route::post('/remover/{produtoId}', [CarrinhosController::class, 'removerProduto'])->name('carrinho.remover');
            Route::get('/info', [CarrinhosController::class, 'info'])->name('carrinho.informacoes');
            Route::get('/finalizar', [CarrinhosController::class, 'finalizar'])->name('carrinho.finalizar');
            Route::put('atualizar/pedido/{id}', [CarrinhosController::class, 'atualizar'])->name('carrinho.atualizar-pedido');
            Route::post('/finalizar-pedido', [CarrinhosController::class, 'finalizarPedido'])->name('carrinho.finalizarPedido');
        });
    });
});

Route::prefix('produtos')->group(function () {
    Route::get('/masculinos', [ProdutosController::class, 'masculinos'])->name('produtos.masculinos');
    Route::get('/femininos', [ProdutosController::class, 'femininos'])->name('produtos.femininos');
    Route::get('/masculinos/camisetas', [ProdutosController::class, 'masculinasCamisetas'])->name('produtos.masculinasCamisetas');
    Route::get('/masculinos/shorts', [ProdutosController::class, 'masculinosShorts'])->name('produtos.masculinosShorts');
    Route::get('/ofertasM', [ProdutosController::class, 'ofertasM'])->name('produtos.ofertasM');
    Route::get('/ofertasF', [ProdutosController::class, 'ofertasF'])->name('produtos.ofertasF');

    Route::get('/femininos/tops', [ProdutosController::class, 'femininosTops'])->name('produtos.femininosTops');
    Route::get('/femininos/legging', [ProdutosController::class, 'femininosLegging'])->name('produtos.femininosLegging');
    Route::get('/femininos/shorts', [ProdutosController::class, 'femininosShorts'])->name('produtos.femininosShorts');
});

Route::middleware(['auth'])->group(function () {
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::prefix('financeiro')->group(function () {
        Route::get('/', [FinanceiroController::class, 'index'])->name('financeiro.index');
    });

    Route::prefix('Medidas')->group(function () {
        Route::get('/create', [TabelaMedidasController::class, 'create'])->name('medidas.create');
        Route::post('/store', [TabelaMedidasController::class, 'store'])->name('medidas.store');
        Route::get('/{produto}/show', [TabelaMedidasController::class, 'show'])->name('medidas.show');


    });

    // Coleções
    Route::prefix('colecao')->group(function () {
        Route::get('/', [ColecoesController::class, 'create'])->name('colecoes.create');
        Route::get('/index', [ColecoesController::class, 'index'])->name('colecoes.index');
        Route::post('/store', [ColecoesController::class, 'store'])->name('colecoes.store');
        Route::get('/show/{id}', [ColecoesController::class, 'show'])->name('colecoes.show');
        Route::get('/edit/{id}', [ColecoesController::class, 'edit'])->name('colecoes.edit');
        Route::put('/update/{id}', [ColecoesController::class, 'update'])->name('colecoes.update');
        Route::delete('/destroy/{id}', [ColecoesController::class, 'destroy'])->name('colecoes.delete');
        Route::get('/{id}/produtos', [ColecoesController::class, 'produtosPorColecao'])->name('colecao.produtos');
    });

    // Produtos
    Route::prefix('produtos')->group(function () {
        Route::get('/', [ProdutosController::class, 'create'])->name('produtos.create');
        Route::get('/index', [ProdutosController::class, 'index'])->name('produtos.index');
        Route::post('/store', [ProdutosController::class, 'store'])->name('produtos.store');
        Route::get('/edit/{id}', [ProdutosController::class, 'edit'])->name('produtos.edit');
        Route::put('/update/{id}', [ProdutosController::class, 'update'])->name('produtos.update');
        Route::delete('/remover-imagem/{id}', [ProdutosController::class, 'removerImagem'])->name('produtos.remover-imagem');
        Route::delete('/destroy/{id}', [ProdutosController::class, 'destroy'])->name('produtos.destroy');
    });


    // Users
    Route::prefix('users')->group(function () {
        Route::get('/', [UsersController::class, 'create'])->name('users.create');
        Route::get('/index', [UsersController::class, 'index'])->name('users.index');
        Route::get('/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::post('/store', [UsersController::class, 'store'])->name('users.store');
        Route::put('/{id}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/destroy/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
    });


    Route::prefix('cupons')->group(function () {
        Route::get('/', [CuponsController::class, 'create'])->name('cupons.create');
        Route::post('/', [CuponsController::class, 'store'])->name('cupons.store');
        Route::get('/lista', [CuponsController::class, 'index'])->name('cupons.index');
        Route::get('/{cupom}/editar', [CuponsController::class, 'edit'])->name('cupons.edit');
        Route::put('/{cupom}', [CuponsController::class, 'update'])->name('cupons.update');
        Route::delete('/{cupom}', [CuponsController::class, 'destroy'])->name('cupons.destroy');
    });



    Route::prefix('pedidos')->group(function () {
        Route::get('/', [PedidosController::class, 'index'])->name('pedidos.index');
        Route::post('/aplicar-cupom-carrinho', [PedidosController::class, 'aplicarCupomCarrinho'])->name('carrinho.aplicar-cupom');
        Route::put('/{id}/status', [PedidosController::class, 'atualizarStatus'])->name('pedidos.atualizar-status');
        Route::get('/{id}', [PedidosController::class, 'show'])->name('pedidos.show');
    });


    Route::prefix('checkout')->group(function () {
        Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
        Route::get('/pending', [CheckoutController::class, 'pending'])->name('checkout.pending');
        Route::get('/{id}', [CheckoutController::class, 'checkout'])->name('checkout');
        Route::post('/processar', [CheckoutController::class, 'processarCheckout'])->name('checkout.processar');
        Route::post('/mercadopago/webhook', [CheckoutController::class, 'webhook']);
    });


    Route::get('/calcular-frete', [FreteController::class, 'calcular'])->name('frete.calcular');
});


Route::get('/imagem/{caminho}/{filename}', [ImagensProdutoController::class, 'getImage'])->name('produtos.imagem');



Route::get('/clear-cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');

    return 'Cache limpo com sucesso!';
});

