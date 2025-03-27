<?php

namespace App\Http\Controllers;

use App\Models\Pedidos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Gráfico de Vendas Mensais
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

        // Gráfico de Usuários com id_tipo = 4 por mês
        $usuarios = User::selectRaw('COUNT(*) as total, MONTH(created_at) as mes')
            ->where('id_tipo', 4)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        $mesesUsuarios = [];
        $totalUsuarios = [];

        foreach ($usuarios as $usuario) {
            $mesesUsuarios[] = Carbon::create()->month($usuario->mes)->format('F');
            $totalUsuarios[] = $usuario->total;
        }

        return view('dashboard', compact('meses', 'valores', 'mesesUsuarios', 'totalUsuarios'));
    }

}
