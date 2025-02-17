@extends('layout.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">Editar Coleção</h2>

        <form action="{{ route('colecoes.update', $colecao->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Coleção</label>
                <input type="text" class="form-control" id="nome" name="nome" value="{{ $colecao->nome }}" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea class="form-control" id="descricao" name="descricao">{{ $colecao->descricao }}</textarea>
            </div>

            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem Atual</label><br>
                <img src="{{ asset('storage/' . $colecao->imagem) }}" alt="Imagem da Coleção" class="img-fluid mb-2" style="max-width: 200px;">
                <input type="file" class="form-control" id="imagem" name="imagem">
            </div>

            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="{{ route('colecoes.index') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
@endsection
