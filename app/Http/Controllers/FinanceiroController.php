<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use Carbon\Carbon;

class FinanceiroController extends Controller
{
    public function index()
    {
        $pedidos = Pedidos::selectRaw('SUM(total) as total, MONTH(created_at) as mes')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $meses = [];
        $valores = [];

        foreach ($pedidos as $pedido) {
            $meses[] = Carbon::create()->month($pedido->mes)->format('F');
            $valores[] = $pedido->total;
        }

        return view('financeiro.index', compact('meses', 'valores'));
    }
}
