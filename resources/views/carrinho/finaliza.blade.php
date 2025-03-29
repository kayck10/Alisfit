@extends('Layout.principal')

@section('content')
    <div class="container py-5">
        <h2 class="text-center mt-5 mb-4 fw-bold text-dark">Finalização de Compra</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Resumo do Carrinho -->
                <div class="card shadow rounded">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Resumo do Carrinho</h5>
                    </div>
                    <div class="card-body">
                        <table class="table text-center align-middle">
                            <thead class="table-dark text-white">
                                <tr>
                                    <th>Produto</th>
                                    <th>Imagem</th>
                                    <th>Qtd</th>
                                    <th>Preço</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carrinho->produtos as $produto)
                                    <tr>
                                        <td class="fw-semibold">{{ $produto->nome }}</td>
                                        <td>
                                            @if ($produto->imagens->isNotEmpty())
                                            <img  src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}"
                                                alt="{{ $produto->nome }}" class="miniatura img-custom"
                                                onclick="trocarImagemPrincipal('{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}')" class="img-fluid rounded"
                                                style="width: 70px; height: 70px; object-fit: cover; margin-right: 10px;">
                                        @else
                                            <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão"
                                                class="miniatura img-custom">
                                        @endif
                                        </td>
                                        <td class="fw-bold">{{ $produto->pivot->quantidade }}</td>
                                        <td class="text-success fw-bold">R$
                                            {{ number_format($produto->preco, 2, ',', '.') }}</td>
                                        <td class="text-danger fw-bold">R$
                                            {{ number_format($produto->pivot->quantidade * $produto->preco, 2, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Total do Pedido -->
                <div class="card shadow rounded mt-4">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Total do Pedido</h5>
                    </div>
                    <div class="card-body text-dark">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">Subtotal:
                                <strong class="">R$
                                    {{ number_format($carrinho->produtos->sum(fn($produto) => $produto->pivot->quantidade * $produto->preco), 2, ',', '.') }}</strong>
                            </li>
                            @if (isset($desconto) && $desconto > 0)
                                <li class="list-group-item text-success">Desconto:
                                    -R$ {{ number_format($desconto, 2, ',', '.') }}
                                </li>
                            @endif
                            <div id="infoFrete"></div>
                            @if (session()->has('valorFrete'))
                                <li class="list-group-item">Frete: <strong class="">R$ </strong></li>
                                <li class="list-group-item">Prazo: <strong class=""><span id="prazoEntrega"></span>
                                        dias úteis</strong></li>
                            @endif
                            @if (session()->has('desconto'))
                                <li class="list-group-item text-success">Desconto: -R$
                                    {{ number_format(session('desconto'), 2, ',', '.') }}</li>
                            @endif

                            <li class="list-group-item bg-light">
                                <h5 class="text-success fw-bold" id="totalPedido" data-total="{{ $total }}">Total:
                                    R$
                                    {{ number_format($total + (float) session('valorFrete', 0) - (float) session('desconto', 0), 2, ',', '.') }}
                                </h5>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Calcular Frete -->
                <div class="card shadow rounded mt-4">
                    <div class="card-header bg-secondary text-white text-center">
                        <h5 class="mb-0">Calcular Frete</h5>
                    </div>
                    <div class="card-body text-center">
                        {{-- <form action="{{ route('frete.calcular') }}" method="POST" class="d-flex justify-content-center">
                            @csrf
                            <input type="text" id="cep" name="cep" class="form-control w-50 me-2 border-primary" placeholder="Digite o CEP" required>
                            <button type="submit" class="btn btn-primary fw-bold">Calcular</button>
                        </form>
                        @if (session()->has('error'))
                        <p class="text-danger mt-2 fw-semibold">{{ session('error') }}</p>
                        @endif --}}
                        <div class="d-flex justify-content-center">
                            <input type="text" id="cepCalcular" name="cep"
                                class="form-control w-50 me-2 border-primary" placeholder="Digite o CEP" required>
                            <button id="calcularFrete" class="btn btn-primary fw-bold">Calcular</button>
                        </div>
                    </div>
                </div>



                <!-- Endereço de Entrega -->

                    <div class="card-body">
                        <!-- Endereço de Entrega -->
                        <div id="enderecoEntrega" class="card shadow rounded mt-4 hide">
                            <div class="card-header bg-secondary text-white text-center">
                                <h5 class="mb-0">Endereço de Entrega</h5>
                            </div>
                            <div class="card-body">
                                <!-- Removido o <form> tradicional -->
                                    <input type="hidden" id="valorFreteHidden" value="0">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="rua" class="form-label fw-semibold">Rua</label>
                                        <input type="text" class="form-control border-primary" id="rua"
                                            name="rua" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="numero" class="form-label fw-semibold">Número</label>
                                        <input type="text" class="form-control border-primary" id="numero"
                                            name="numero" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="bairro" class="form-label fw-semibold">Bairro</label>
                                        <input type="text" class="form-control border-primary" id="bairro"
                                            name="bairro" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cidade" class="form-label fw-semibold">Cidade</label>
                                        <input type="text" class="form-control border-primary" id="cidade"
                                            name="cidade" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="estado" class="form-label fw-semibold">Estado</label>
                                        <input type="text" class="form-control border-primary" id="estado"
                                            name="estado" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="cep" class="form-label fw-semibold">CEP</label>
                                        <input type="text" class="form-control border-primary" id="cep"
                                            name="cep" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="complemento" class="form-label fw-semibold">Complemento</label>
                                        <input type="text" class="form-control border-primary" id="complemento"
                                            name="complemento">
                                    </div>
                                </div>

                                <!-- Botão modificado - agora sem type="submit" -->
                                <button id="finalizarPedido" class="btn btn-success w-100 btn-lg mt-4 fw-bold">
                                    <i class="fas fa-check-circle"></i> Finalizar e Pagar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
