<?php

namespace App\Http\Controllers;

use App\Models\Colecoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ColecoesController extends Controller
{
    public function create()
    {
        return view('colecoes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagem')) {
            $imagePath = $request->file('imagem')->store('colecoes', 'public');
        }

        Colecoes::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'imagem' => $imagePath ?? null,
        ]);

        return redirect()->route('colecoes.create')->with('success', 'Coleção criada com sucesso!');
    }

    public function index()
    {
        $colecoes = Colecoes::all();
        return view('colecoes.index', compact('colecoes'));
    }

    public function show($id)
    {
        $colecao = Colecoes::findOrFail($id);

        return response()->json([
            'id' => $colecao->id,
            'nome' => $colecao->nome,
            'descricao' => $colecao->descricao ?? 'Sem descrição',
            'imagem_url' => asset('storage/' . $colecao->imagem),
        ]);
    }

    public function edit($id)
    {
        $colecao = Colecoes::findOrFail($id);
        return view('colecoes.edit', compact('colecao'));
    }

    public function update(Request $request, $id)
    {
        $colecao = Colecoes::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('imagem')) {
            if ($colecao->imagem) {
                Storage::disk('public')->delete($colecao->imagem);
            }
            $imagePath = $request->file('imagem')->store('colecoes', 'public');
            $colecao->imagem = $imagePath;
        }

        $colecao->nome = $request->nome;
        $colecao->descricao = $request->descricao;
        $colecao->save();

        return redirect()->route('colecoes.index')->with('success', 'Coleção atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $colecao = Colecoes::findOrFail($id);

        if ($colecao->imagem) {
            Storage::disk('public')->delete($colecao->imagem);
        }

        $colecao->delete();
        return redirect()->route('colecoes.index')->with('success', 'Coleção excluída com sucesso!');
    }
}
