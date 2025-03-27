<?php

namespace App\Http\Controllers;

use App\Services\FreteService;
use Illuminate\Http\Request;

class FreteController extends Controller
{
    protected $freteService;

    public function __construct(FreteService $freteService)
    {
        $this->freteService = $freteService;
    }

    public function calcular(Request $request)
    {
        $request->validate([
            'cep' => 'required|string|min:8|max:9',
        ]);

        $cepDestino = $request->cep;
        $frete = $this->freteService->calcularFrete($cepDestino);

        if (isset($frete['error'])) {
            return redirect()->back()->with('error', $frete['error']);
        }
        return response()->json($frete);
    }

}
