<?php

namespace App\Http\Controllers;

use App\Services\FreteService;
use Illuminate\Http\Request;

class FreteController extends Controller
{
    protected $correiosService;

    public function __construct(FreteService $correiosService)
    {
        $this->correiosService = $correiosService;
    }

    public function calcular(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|min:8|max:9',
        ]);

        $cepDestino = $request->cep;
        $frete = $this->correiosService->calcularFrete($cepDestino);

        if (isset($frete['error'])) {
            return redirect()->back()->with('error', $frete['error']);
        }

        return redirect()->back()->with([
            'valorFrete' => $frete['valor'],
            'prazoFrete' => $frete['prazo']
        ]);
    }
}
