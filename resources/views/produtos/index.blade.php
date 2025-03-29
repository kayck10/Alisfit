@extends('Layout.app')

@section('content')
    <style>
        .img-custom {
            width: auto;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>

    <div class="container">
        <h2 class="mb-4">Lista de Produtos</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="margin-bottom: 150px;" class="row">
            @foreach ($produtos as $produto)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        @if ($produto->imagens->isNotEmpty())
                            {{-- @dd($produto->imagens->first()->imagem) --}}

                            <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}"
                                alt="{{ $produto->nome }}" class="img-custom">


                            {{-- <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}"
                             alt="{{ $produto->nome }}" class="img-custom"> --}}
                        @else
                            {{-- <img src="{{ asset('images/sem-imagem.png') }}" alt="Imagem não disponível" class="img-custom"> --}}
                        @endif

                        <div class="card-body">
                            <h5 class="card-title">{{ $produto->nome }}</h5>
                            <p class="card-text"><strong>Preço:</strong> R$
                                {{ number_format($produto->preco, 2, ',', '.') }}</p>
                            <button class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#produtoModal{{ $produto->id }}">
                                Ver Detalhes
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal de Detalhes do Produto -->
                <div class="modal fade" id="produtoModal{{ $produto->id }}" tabindex="-1"
                    aria-labelledby="produtoModalLabel{{ $produto->id }}" aria-hidden="true">

                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="produtoModalLabel{{ $produto->id }}">{{ $produto->nome }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Fechar"></button>
                            </div>
                            <div class="modal-body">


                                <p><strong>Descrição:</strong> {{ $produto->descricao }}</p>
                                <p><strong>Preço:</strong> R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                                <p><strong>Coleção:</strong> {{ $produto->colecao->nome ?? 'Não informado' }}</p>

                                <p><strong>Tamanhos Disponíveis:</strong></p>
                                <ul>
                                    @foreach ($produto->tamanhos as $tamanho)
                                        <li>{{ $tamanho->desc }} - {{ $tamanho->pivot->quantidade }} unidades</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('produtos.edit', $produto->id) }}" class="btn btn-warning">Editar</a>

                                <!-- Excluir -->
                                <form action="{{ route('produtos.destroy', $produto->id) }}" method="POST"
                                    class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Excluir</button>
                                </form>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
