@extends('Layout.principal')

@section('content')
    <main class="col-md-9 offset-md-3">
        <div class="container">
            <h3 class="mb-4 text-center">Leggings Femininas</h3>

            @if ($produtos->isEmpty())
                <div class="alert alert-warning text-center">
                    Nenhuma legging disponível no momento.
                </div>
            @else
                <div class="row">
                    @foreach ($produtos as $produto)
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="product-card">
                                <a href="{{ route('produto.detalhes', ['id' => $produto->id]) }}">
                                    @if ($produto->imagens->isNotEmpty())
                                        <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}"
                                            alt="{{ $produto->nome }}">
                                    @endif
                                </a>
                                <p class="product-name">{{ $produto->nome }}</p>
                                <p class="product-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>

                                <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary mt-2">Adicionar à sacola</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>
@endsection
