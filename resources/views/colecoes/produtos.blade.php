@extends('layout.app')

@section('content')
    <div class="container">
        <h2 class="text-center mb-4">{{ $colecao->nome }} - Produtos</h2>

        <div class="row">
            @foreach ($produtos as $produto)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card shadow-sm">
                        <img src="{{ asset('storage/' . $produto->imagem) }}" class="card-img-top" alt="{{ $produto->nome }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title">{{ $produto->nome }}</h5>
                            <p class="card-text">{{ $produto->descricao ?? 'Sem descrição' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <a href="{{ route('colecoes.index') }}" class="btn btn-secondary mt-4">Voltar para as Coleções</a>
    </div>
@endsection
