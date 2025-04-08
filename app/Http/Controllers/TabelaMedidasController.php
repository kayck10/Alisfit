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

    public function show($produto_id = null)
    {
        if ($produto_id) {
            $produto = Produtos::with(['medidas' => function ($query) {
                $query->orderBy('tamanho');
            }])->findOrFail($produto_id);

            $medidas = $produto->medidas;
        } else {
            $medidas = TabelaMedidas::with('produto')->orderBy('produto_id')->orderBy('tamanho')->get();
            $produto = null;
        }

        $produtos = Produtos::has('medidas')->withCount('medidas')->get();
        return view('medidas.show', compact('medidas', 'produtos', 'produto'));
    }

    public function update(Request $request, TabelaMedidas $medida)
    {
        $request->validate([
            'tamanho' => 'required|string|max:10',
            'torax' => 'nullable|numeric',
            'cintura' => 'nullable|numeric',
            'quadril' => 'nullable|numeric',
            'comprimento' => 'nullable|numeric',
            'altura' => 'nullable|numeric',
            'observacoes' => 'nullable|string|max:500'
        ]);

        $medida->update($request->all());

        return redirect()->route('medidas.show', $medida->produto_id)
            ->with('success', 'Medidas atualizadas com sucesso!');
    }

    public function destroy(TabelaMedidas $medida)
    {
        $produto_id = $medida->produto_id;
        $medida->delete();

        return redirect()->route('medidas.show', $produto_id)
            ->with('success', 'Medida removida com sucesso!');
    }
}
