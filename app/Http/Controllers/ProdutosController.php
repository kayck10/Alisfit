<?php

namespace App\Http\Controllers;

use App\Models\Colecoes;
use App\Models\imagensProduto;
use App\Models\Produtos;
use App\Models\ProdutosTamanhos;
use App\Models\Tamanhos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdutosController extends Controller
{
    public function create()
    {
        $colecoes = Colecoes::all();
        $tamanhos = Tamanhos::all();
        return view('produtos.create', compact('colecoes', 'tamanhos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'colecao_id' => 'required|exists:colecoes,id',
            'imagens' => 'required|array',
            'imagens.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'informacoes' => 'required|array',
            'informacoes.*.cor' => 'required|string',
            'informacoes.*.tamanhos' => 'required|exists:tamanhos,id',
            'informacoes.*.quantidades' => 'required|integer|min:1',
        ]);

        $produto = Produtos::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'colecao_id' => $request->colecao_id,
        ]);

        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $imagem) {
                $imagePath = $imagem->store('produtos', 'public');
                ImagensProduto::create([
                    'produto_id' => $produto->id,
                    'imagem' => $imagePath,
                ]);
            }
        }

        foreach ($request->informacoes as $info) {
            ProdutosTamanhos::create([
                'produto_id' => $produto->id,
                'cor' => $info['cor'],
                'tamanho_id' => $info['tamanhos'],
                'quantidade' => $info['quantidades'],
            ]);
        }

        return redirect()->route('produtos.create')->with('success', 'Produto criado com sucesso!');
    }

    public function index()
    {
        $produtos = Produtos::with(['colecao', 'tamanhos'])->get();
        return view('produtos.index', compact('produtos'));
    }

    public function edit($id)
    {
        $produto = Produtos::with('tamanhos')->findOrFail($id);
        $colecoes = Colecoes::all();
        $tamanhos = Tamanhos::all();

        return view('produtos.edit', compact('produto', 'colecoes', 'tamanhos'));
    }


    public function update(Request $request, $id)
    {
        $produto = Produtos::findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'colecao_id' => 'required|exists:colecoes,id',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('imagem')) {
            if ($produto->imagem) {
                Storage::disk('public')->delete($produto->imagem->caminho);
                $produto->imagem->delete();
            }

            $imagePath = $request->file('imagem')->store('produtos', 'public');
            $imagem = ImagensProduto::create([
                'caminho' => $imagePath
            ]);
            $produto->imagem_id = $imagem->id;
        }

        $produto->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'colecao_id' => $request->colecao_id,
        ]);

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso!');
    }


    public function destroy($id)
    {
        $produto = Produtos::findOrFail($id);

        if ($produto->imagem) {
            Storage::delete('public/' . $produto->imagem);
        }

        $produto->delete();
        return redirect()->route('produtos.index')->with('success', 'Produto excluído com sucesso!');
    }
}
