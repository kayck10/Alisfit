<?php

namespace App\Http\Controllers;

use App\Models\TiposProdutos;
use Illuminate\Http\Request;

class TiposProdutosController extends Controller
{
    public function index()
    {
        $tipos = TiposProdutos::all();
        return view('tipos_produtos.index', compact('tipos'));
    }

    public function create()
    {
        return view('tipos_produtos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'desc' => 'required|string|max:255',
        ]);

        TiposProdutos::create([
            'desc' => $request->desc,
        ]);

        return redirect()->route('tipos-produtos.index')->with('success', 'Tipo de produto criado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'desc' => 'required|string|max:255',
        ]);

        $tipo = TiposProdutos::findOrFail($id);
        $tipo->update([
            'desc' => $request->desc,
        ]);

        return redirect()->route('tipos-produtos.index')->with('success', 'Tipo de produto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $tipo = TiposProdutos::findOrFail($id);
        $tipo->delete();

        return redirect()->route('tipos-produtos.index')->with('success', 'Tipo de produto excluído com sucesso!');
    }
}
