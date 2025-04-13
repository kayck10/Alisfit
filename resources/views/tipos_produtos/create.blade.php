@extends('Layout.app')

@section('content')
<div class="container mt-4">
    <h2>Cadastrar Tipo de Peça</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $erro)
                    <li>{{ $erro }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tipos-produtos.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="desc" class="form-label">Descrição</label>
            <input type="text" name="desc" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="{{ route('tipos-produtos.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
