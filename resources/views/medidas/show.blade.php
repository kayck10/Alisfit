@extends('Layout.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Medidas do Produto: {{ $produto->nome }}</h2>
        <a href="{{ route('medidas.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-2"></i>Adicionar Mais Medidas
        </a>
    </div>

    @if($medidas->isEmpty())
        <div class="alert alert-info">Nenhuma medida cadastrada para este produto ainda.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Tamanho</th>
                        <th>Tórax</th>
                        <th>Cintura</th>
                        <th>Quadril</th>
                        <th>Comprimento</th>
                        <th>Ombro</th>
                        <th>Manga</th>
                        <th>Observações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($medidas as $medida)
                    <tr>
                        <td>{{ $medida->tamanho }}</td>
                        <td>{{ $medida->torax ? $medida->torax.' cm' : '-' }}</td>
                        <td>{{ $medida->cintura ? $medida->cintura.' cm' : '-' }}</td>
                        <td>{{ $medida->quadril ? $medida->quadril.' cm' : '-' }}</td>
                        <td>{{ $medida->comprimento ? $medida->comprimento.' cm' : '-' }}</td>
                        <td>{{ $medida->ombro ? $medida->ombro.' cm' : '-' }}</td>
                        <td>{{ $medida->manga ? $medida->manga.' cm' : '-' }}</td>
                        <td>{{ $medida->observacoes ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <a href="{{ route('medidas.create') }}" class="btn btn-outline-primary mt-3">
        <i class="fas fa-arrow-left mr-2"></i>Voltar para adicionar medidas
    </a>
</div>
@endsection
