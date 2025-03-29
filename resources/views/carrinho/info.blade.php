@extends('Layout.principal')

@section('content')
    <div style="margin-top: 100px; margin-bottom: 100px;" class="container">
        <h2 class="mb-4">Informações do Carrinho</h2>

        @if (!$carrinho || $carrinho->produtos->isEmpty())
            <p>Seu carrinho está vazio.</p>
        @else
            <div class="card">
                <div class="card-body">
                    <ul class="list-group">
                        @php $totalCarrinho = 0; @endphp

                        @foreach ($carrinho->produtos as $produto)
                            @php
                                $subtotal = $produto->preco * $produto->pivot->quantidade;
                                $totalCarrinho += $subtotal;
                            @endphp
                            <li class="list-group-item d-flex align-items-center">
                                @if ($produto->imagens->isNotEmpty())
                                <img  src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}"
                                    alt="{{ $produto->nome }}" class="miniatura img-custom"
                                    onclick="trocarImagemPrincipal('{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}')" class="img-fluid rounded"
                                    style="width: 70px; height: 70px; object-fit: cover; margin-right: 10px;">
                            @else
                                <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão"
                                    class="miniatura img-custom">
                            @endif
                                <div class="me-auto">
                                    <strong>{{ $produto->nome }}</strong> <br>
                                    <small>R$ {{ number_format($produto->preco, 2, ',', '.') }}</small> <br>
                                    <small><strong>Quantidade:</strong> {{ $produto->pivot->quantidade }}</small> <br>
                                    <small><strong>Tamanho:</strong> {{ $produto->pivot->tamanho_id }}</small> <br>
                                    <small><strong>Cor:</strong></small>
                                    <span
                                        style="display: inline-block; width: 20px; height: 20px; background: {{ $produto->pivot->cor }}; border: 1px solid #000;"></span>
                                    <br>
                                    <small id="subtotal"><strong>Subtotal:</strong> R$
                                        {{ number_format($subtotal, 2, ',', '.') }}</small>

                                </div>

                                <form class="update-quantity-form" data-produto-id="{{ $produto->pivot->produto_id }}">
                                    @csrf
                                    <input type="number" name="quantidade"
                                        class="form-control form-control-sm text-center mx-2 update-quantity"
                                        value="{{ $produto->pivot->quantidade }}" min="1" style="width: 60px;">
                                </form>

                                <form action="{{ route('carrinho.remover', $produto->pivot->produto_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-transparent btn-sm">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>

                    <hr>

                    <!-- Se o cupom foi aplicado, mostre o valor do desconto -->
                    @if (session('desconto'))
                        <div class="alert alert-success">
                            <strong>Desconto Aplicado:</strong> R$ {{ number_format(session('desconto'), 2, ',', '.') }}
                        </div>
                        @php
                            $totalCarrinho -= session('desconto');
                        @endphp
                    @endif

                    <h5 class="text-end">
                        <strong id="total">Total: R$ {{ number_format($total, 2, ',', '.') }}</strong>
                    </h5>

                    <hr>

                    <!-- Formulário para aplicar o cupom -->
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('carrinho.aplicar-cupom') }}" method="POST" class="d-flex">
                            @csrf
                            <input type="hidden" name="carrinhoId" value="{{ $carrinho->id }}">
                            <input type="text" name="codigoCupom" class="form-control"
                                placeholder="Digite o código do cupom" required>
                            <button type="submit" class="btn btn-dark ms-2">Aplicar Cupom</button>
                        </form>
                    </div>

                    <div class="mt-3 text-end">
                        <a href="{{ route('carrinho.finalizar') }}" class="btn btn-dark">Finalizar Compra</a>
                    </div>
                </div>
            </div>
        @endif
    </div>


@endsection
