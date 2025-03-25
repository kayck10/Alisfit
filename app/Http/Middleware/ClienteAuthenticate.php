<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Brian2694\Toastr\Facades\Toastr as FacadesToastr;

class ClienteAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            FacadesToastr::warning('Faça login para concluir a ação', 'Atenção', ["positionClass" => "toast-top-center"]);
            return redirect()->back();
        }

        return $next($request);
    }

}
