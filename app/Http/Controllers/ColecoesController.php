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
            'imagem' => 'required|image|mimes:jpeg,png,jpg,gif|max:5024',
            'status' => 'nullable|in:rascunho,publicado',
        ]);

        if ($request->hasFile('imagem')) {
            $imagePath = $request->file('imagem')->store('colecoes', 'public');
        }

        Colecoes::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'imagem' => $imagePath ?? null,
            'status' => $request->status ?? 'rascunho',
            'publicado_em' => $request->status == 'publicado' ? now() : null
        ]);

        return redirect()->route('colecoes.create')->with('success', 'Coleção criada com sucesso!');
    }

    public function index()
    {
        // Mostra todas as coleções (incluindo rascunhos) para o admin
        $colecoes = Colecoes::latest()->get();
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
            'status' => $colecao->status
        ]);
    }

    public function update(Request $request, $id)
    {
        $colecao = Colecoes::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:rascunho,publicado' // Adicione esta validação
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
        $colecao->status = $request->status ?? $colecao->status;

        // Se estiver publicando, define a data de publicação
        if ($request->status == 'publicado' && $colecao->status != 'publicado') {
            $colecao->publicado_em = now();
        }

        $colecao->save();

        return redirect()->route('colecoes.index')->with('success', 'Coleção atualizada com sucesso!');
    }

    // Novo método para alterar status
    public function toggleStatus($id)
    {
        $colecao = Colecoes::findOrFail($id);

        $colecao->status = $colecao->status == 'publicado' ? 'rascunho' : 'publicado';

        if ($colecao->status == 'publicado') {
            $colecao->publicado_em = now();
        }

        $colecao->save();

        return back()->with('success', 'Status da coleção atualizado!');
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
