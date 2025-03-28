<?php

namespace App\Http\Controllers;

use App\Models\Carrinhos;
use App\Models\Colecoes;
use App\Models\Generos;
use App\Models\imagensProduto;
use App\Models\ImagensProdutos;
use App\Models\Produtos;
use App\Models\ProdutosTamanhos;
use App\Models\Tamanhos;
use App\Models\TiposProdutos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProdutosController extends Controller
{
    public function create()
    {
        $colecoes = Colecoes::all();
        $tamanhos = Tamanhos::all();
        $pecas =  TiposProdutos::all();
        $generos = Generos::all();
        return view('produtos.create', compact('colecoes', 'tamanhos', 'generos', 'pecas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'colecao_id' => 'required|exists:colecoes,id',
            'genero_id' => 'required|exists:generos,id',
            'tipo_produto_id' => 'required|exists:tipos_produtos,id',
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
            'genero_id' => $request->genero_id,
            'tipo_produto_id' => $request->tipo_produto_id,
        ]);

        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $imagem) {
                $imagePath = $imagem->store('produtos', 'public');
                ImagensProdutos::create([
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
            $imagem = ImagensProdutos::create([
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

    public function masculinos()
    {
        $produtos = Produtos::with(['imagens', 'colecao', 'tamanhos'])
            ->where('genero_id', 1)
            ->get();

        return view('produtos.masculinos', compact('produtos'));
    }

    public function femininos()
    {
        $produtos = Produtos::with(['imagens', 'colecao', 'tamanhos'])
            ->where('genero_id', 2)
            ->get();

        return view('produtos.femininos', compact('produtos'));
    }

    public function masculinasCamisetas()
    {
        $masculino = Generos::where('desc', 'Masculino')->first();
        $camiseta = TiposProdutos::where('desc', 'Camiseta')->first();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();


        if (!$masculino || !$camiseta) {
            return redirect()->route('loja.create')->with('error', 'Categoria não encontrada.');
        }

        $produtos = Produtos::where('genero_id', $masculino->id)
            ->where('tipo_produto_id', $camiseta->id)
            ->get();

        return view('produtos.masculinasCamisetas', compact('produtos', 'carrinho'));
    }

    public function masculinosShorts()
    {
        $masculino = Generos::where('desc', 'Masculino')->first();
        $shorts = TiposProdutos::where('desc', 'Shorts')->first();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();


        if (!$masculino || !$shorts) {
            return redirect()->route('loja.create')->with('error', 'Categoria não encontrada.');
        }

        $produtos = Produtos::where('genero_id', $masculino->id)
            ->where('tipo_produto_id', $shorts->id)
            ->get();

        return view('produtos.masculinosShorts', compact('produtos', 'carrinho'));
    }

    public function femininosTops()
    {
        $feminino = Generos::where('desc', 'Feminino')->first();
        $tops = TiposProdutos::where('desc', 'Tops')->first();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        if (!$feminino || !$tops) {
            return redirect()->route('loja.create')->with('error', 'Categoria não encontrada.');
        }

        $produtos = Produtos::where('genero_id', $feminino->id)
            ->where('tipo_produto_id', $tops->id)
            ->get();

        return view('produtos.femininosTops', compact('produtos', 'carrinho'));
    }

    public function femininosLegging()
    {
        $feminino = Generos::where('desc', 'Feminino')->first();
        $legging = TiposProdutos::where('desc', 'Legging')->first();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        if (!$feminino || !$legging) {
            return redirect()->route('loja.create')->with('error', 'Categoria não encontrada.');
        }

        $produtos = Produtos::where('genero_id', $feminino->id)
            ->where('tipo_produto_id', $legging->id)
            ->get();

        return view('produtos.femininosLeggings', compact('produtos', 'carrinho'));
    }

    public function femininosShorts()
    {
        $feminino = Generos::where('desc', 'Feminino')->first();
        $shorts = TiposProdutos::where('desc', 'Shorts')->first();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();

        if (!$feminino || !$shorts) {
            return redirect()->route('loja.create')->with('error', 'Categoria não encontrada.');
        }

        $produtos = Produtos::where('genero_id', $feminino->id)
            ->where('tipo_produto_id', $shorts->id)
            ->get();

        return view('produtos.femininosShorts', compact('produtos', 'carrinho'));
    }
}
