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
use Brian2694\Toastr\Facades\Toastr;
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
            'destaque' => 'nullable|boolean', // Adicione esta validação
            'imagens' => 'required|array',
            'imagens.*' => 'image|mimes:jpeg,png,jpg,gif|',
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
            'destaque' => $request->boolean('destaque'), // Salva como true/false
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

        Toastr::success('Produto criado com sucesso!', 'Sucesso', ["positionClass" => "toast-top-center"]);
        return redirect()->route('produtos.create')->with('success');
    }
    public function index()
    {
        $produtos = Produtos::with(['colecao', 'tamanhos', 'imagens'])->get();
        return view('produtos.index', compact('produtos'));
    }

    public function edit($id)
    {
        $produto = Produtos::with('colecao', 'tamanhos', 'imagens')->findOrFail($id);
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
            'destaque' => 'nullable|boolean',
            'imagens' => 'nullable|array',
            'imagens.*' => 'image|mimes:jpeg,png,jpg,gif|max:51200',
            'informacoes' => 'sometimes|array',
            'informacoes.*.tamanhos' => 'required|exists:tamanhos,id',
            'informacoes.*.cor' => 'required|string',
            'informacoes.*.quantidades' => 'required|integer|min:1'
        ]);

        $produto->update([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'preco' => $request->preco,
            'colecao_id' => $request->colecao_id,
            'destaque' => $request->boolean('destaque'),
        ]);

        // Adicionar novas imagens
        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $imagem) {
                $imagePath = $imagem->store('produtos', 'public');
                ImagensProdutos::create([
                    'produto_id' => $produto->id,
                    'imagem' => $imagePath,
                ]);
            }
        }

        // Atualizar as informações de tamanhos e quantidades
        if ($request->has('informacoes')) {
            $produto->tamanhos()->detach();
            foreach ($request->informacoes as $info) {
                if (!empty($info['tamanhos']) && !empty($info['quantidades'])) {
                    $produto->tamanhos()->attach($info['tamanhos'], [
                        'quantidade' => $info['quantidades'],
                        'cor' => $info['cor'] ?? null
                    ]);
                }
            }
        }

        Toastr::success('Produto atualizado com sucesso!', 'Sucesso', ["positionClass" => "toast-top-center"]);
        return redirect()->route('produtos.index')->with('success');
    }

    public function removerImagem($id)
    {
        $imagem = ImagensProdutos::findOrFail($id);

        Storage::disk('public')->delete($imagem->imagem);

        $imagem->delete();

        return response()->json(['success' => true]);
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
        $camiseta = TiposProdutos::where('desc', 'Camisa', 'Camiseta')->first();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();


        if (!$masculino || !$camiseta) {
            return redirect()->route('loja.create')->with('error', 'Categoria não encontrada.');
        }

        $produtos = Produtos::where('genero_id', $masculino->id)
            ->where('tipo_produto_id', $camiseta->id)
            ->get();

        return view('produtos.masculinasCamisetas', compact('produtos', 'carrinho'));
    }

    public function ofertasM()
    {
        $masculino = Generos::where('desc', 'Masculino')->first();
        $camiseta = TiposProdutos::where('desc', 'Conjuntos')->first();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();


        if (!$masculino || !$camiseta) {
            return redirect()->route('loja.create')->with('error', 'Categoria não encontrada.');
        }

        $produtos = Produtos::where('genero_id', $masculino->id)
            ->where('tipo_produto_id', $camiseta->id)
            ->get();

        return view('produtos.ofertasM', compact('produtos', 'carrinho'));
    }

    public function ofertasF()
    {
        $masculino = Generos::where('desc', 'Feminino')->first();
        $camiseta = TiposProdutos::where('desc', 'Conjuntos')->first();
        $carrinho = Carrinhos::with('produtos')->where('user_id', Auth::id())->first();


        if (!$masculino || !$camiseta) {
            return redirect()->route('loja.create')->with('error', 'Categoria não encontrada.');
        }

        $produtos = Produtos::where('genero_id', $masculino->id)
            ->where('tipo_produto_id', $camiseta->id)
            ->get();

        return view('produtos.OfertasF', compact('produtos', 'carrinho'));
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
        $legging = TiposProdutos::where('desc', 'Calça Legging')->first();
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
