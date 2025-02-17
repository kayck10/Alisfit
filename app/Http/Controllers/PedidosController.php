<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PedidosController extends Controller
{
    public function index()
    {
        $pedidos = [
            (object)[ 'id' => 1, 'cliente' => 'João Silva', 'status' => 'pendente', 'created_at' => '2025-01-01 10:00' ],
            (object)[ 'id' => 2, 'cliente' => 'Maria Oliveira', 'status' => 'concluido', 'created_at' => '2025-01-02 14:30' ],
            (object)[ 'id' => 3, 'cliente' => 'Carlos Souza', 'status' => 'cancelado', 'created_at' => '2025-01-03 09:45' ],
            (object)[ 'id' => 4, 'cliente' => 'Ana Costa', 'status' => 'pendente', 'created_at' => '2025-01-04 11:15' ],
            (object)[ 'id' => 5, 'cliente' => 'Lucas Pereira', 'status' => 'concluido', 'created_at' => '2025-01-05 16:20' ],
        ];

        return view('pedidos.index', compact('pedidos'));
    }
}

