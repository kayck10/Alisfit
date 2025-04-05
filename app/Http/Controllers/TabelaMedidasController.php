<?php

namespace App\Http\Controllers;

use App\Models\Produtos;
use App\Models\TabelaMedidas;
use Illuminate\Http\Request;

class TabelaMedidasController extends Controller
{
    public function create()
    {
        $produtos = Produtos::all();
        return view('medidas.create', compact('produtos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'tamanho' => 'required|string|max:10',
            'torax' => 'nullable|numeric|min:0',
            'cintura' => 'nullable|numeric|min:0',
            'quadril' => 'nullable|numeric|min:0',
            'comprimento' => 'nullable|numeric|min:0',
            'altura' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string|max:255'
        ]);

        TabelaMedidas::create($request->all());
        toastr()->success('Medidas cadastradas com sucesso!', 'Sucesso', ["positionClass" => "toast-top-center"]);
        return redirect()->back();
    }


}
