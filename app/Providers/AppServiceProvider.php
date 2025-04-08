<?php

namespace App\Providers;

use App\Models\Colecoes;
use App\Models\Produtos;
use Illuminate\Contracts\View\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->share('colecoes', Colecoes::all());
        view()->share('produto', Produtos::all());

    }
}
