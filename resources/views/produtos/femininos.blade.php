@extends('layout.principal')

@section('content')
<div style="margin-top: 100px;" class="container">
    <h3 class="mb-5">Roupas Femininas</h3>

    @if($produtos->isEmpty())
        <div class="alert alert-warning text-center mt-4">
            <h4>Nenhum produto feminino disponível no momento.</h4>
            <p>Confira novamente mais tarde!</p>
        </div>
    @else
        <div class="row">
            @foreach($produtos as $produto)
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        @if($produto->imagens->isNotEmpty())
                            <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}" class="card-img-top" alt="{{ $produto->nome }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $produto->nome }}</h5>
                            <p class="card-text">{{ $produto->descricao }}</p>
                            <p><strong>Preço:</strong> R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                            <a href="{{ route('produto.detalhes', $produto->id) }}" class="btn btn-primary">Ver Detalhes</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
