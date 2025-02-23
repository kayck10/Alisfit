<?php

use App\Http\Controllers\CarrinhosController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ColecoesController;
use App\Http\Controllers\CuponsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FreteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\PrincipalController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LoginController::class, 'login'])->name('login');

Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::prefix('loja')->group(function () {
    Route::get('/', [PrincipalController::class, 'index'])->name('loja.create');
    Route::get('/teste', [PrincipalController::class, 'teste'])->name('teste');
    Route::post('/ajax-request', [PrincipalController::class, 'handleRequest'])->name('ajax.request');
    Route::get('produtos', [PrincipalController::class, 'produtos'])->name('produtos');
    Route::get('drops', [PrincipalController::class, 'drops'])->name('drops');
    Route::get('produto/{id}', [PrincipalController::class, 'produtoDetalhes'])->name('produto.detalhes');
});

// Route::middleware(['auth'])->group(function () {
// Dashboard
Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
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
    Route::post('/aplicar-cupom', [PedidosController::class, 'retornarDescontoCupom'])->name('pedidos.aplicar-cupom');
});

Route::prefix('carrinho')->group(function () {
    Route::get('/', [CarrinhosController::class, 'carrinho'])->name('carrinho');
    Route::post('/adicionar/{produtoId}', [CarrinhosController::class, 'adicionarProduto'])->name('carrinho.adicionar');
    Route::post('/atualizar/{produtoId}', [CarrinhosController::class, 'atualizarQuantidade'])->name('carrinho.atualizar');
    Route::post('/remover/{produtoId}', [CarrinhosController::class, 'removerProduto'])->name('carrinho.remover');
    Route::get('/finalizar', [CarrinhosController::class, 'finalizar'])->name('carrinho.finalizar');
    Route::post('/calcular-frete', [CarrinhosController::class, 'calcularFrete'])->name('frete.calcular');
});



Route::prefix('checkout')->group(function () {

    Route::post('/', [CheckoutController::class, 'checkout'])->name('checkout');
    Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/failure', [CheckoutController::class, 'failure'])->name('checkout.failure');
    Route::get('/pending', [CheckoutController::class, 'pending'])->name('checkout.pending');
});

Route::post('/calcular-frete', [FreteController::class, 'calcular'])->name('frete.calcular');


// });
