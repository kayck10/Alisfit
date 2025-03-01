@extends('Layout.principal')

@section('content')
<div style="margin-top: 100px;" class="container">
    <h2 class="mb-4">Roupas Masculinas</h2>
    <div class="row">
        @foreach($produtos as $produto)
            <div class="col-md-4">
                <div class="card mb-4">
                    @if($produto->imagens->count() > 0)
                        <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}" class="card-img-top" alt="{{ $produto->nome }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $produto->nome }}</h5>
                        <p class="card-text">Preço: R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                        <a href="{{route('produto.detalhes', $produto->id)}}" class="btn btn-dark">Ver Detalhes</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
