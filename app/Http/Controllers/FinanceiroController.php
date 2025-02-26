<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedidos;
use Carbon\Carbon;

class FinanceiroController extends Controller
{
    public function index()
    {
        // Buscar os pedidos agrupados por mês
        $pedidos = Pedidos::selectRaw('SUM(total) as total, MONTH(created_at) as mes')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Criar arrays para o gráfico
        $meses = [];
        $valores = [];

        foreach ($pedidos as $pedido) {
            $meses[] = Carbon::create()->month($pedido->mes)->format('F'); // Nome do mês
            $valores[] = $pedido->total; // Valor total dos pedidos no mês
        }

        return view('financeiro.index', compact('meses', 'valores'));
    }
}
