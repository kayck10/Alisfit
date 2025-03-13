    @extends('Layout.principal')

    @section('content')
        <link rel="stylesheet" href="iziToast.min.css">
        <div class="container py-5">
            <h2 class="text-center mb-5 mt-5">Finalização de Compra</h2>

            <!-- Resumo do Carrinho -->
            <div class="row">
                <div class="col-12">
                    <h4>Resumo do Carrinho</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Imagem</th>
                                    <th>Quantidade</th>
                                    <th>Preço</th>
                                    <th>Subtotal</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($carrinho->produtos as $produto)
                                    <tr>
                                        <td>{{ $produto->nome }}</td>
                                        <td> <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}"
                                                alt="{{ $produto->nome }}"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        </td>
                                        <td>
                                            <form action="{{ route('carrinho.atualizar', $produto->id) }}" method="POST">
                                                @csrf
                                                <input type="number" name="quantidade"
                                                    value="{{ $produto->pivot->quantidade }}" min="1"
                                                    class="form-control" style="width: 80px;">
                                                <button type="submit"
                                                    class="btn btn-link text-decoration-none">Atualizar</button>
                                            </form>
                                        </td>
                                        <td>R$ {{ number_format($produto->preco, 2, ',', '.') }}</td>
                                        <td>R$
                                            {{ number_format($produto->pivot->quantidade * $produto->preco, 2, ',', '.') }}
                                        </td>
                                        <td>
                                            <form action="{{ route('carrinho.remover', $produto->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Totais do Carrinho -->
            <div class="row">
                <div class="col-12 col-md-6">
                    <h4>Total do Carrinho</h4>
                    <ul class="list-group">
                        <li class="list-group-item">Subtotal: R$
                            {{ number_format(
                                $carrinho->produtos->sum(function ($produto) {
                                    return $produto->pivot->quantidade * $produto->preco;
                                }),
                                2,
                                ',',
                                '.',
                            ) }}
                        </li>

                        <form action="{{ route('frete.calcular') }}" method="POST" class="form-inline">
                            @csrf
                            <label for="cep" class="mr-2">CEP:</label>
                            <input type="text" id="cep" name="cep" class="form-control mr-2"
                                placeholder="Digite o CEP" required>
                            <button type="submit" class="btn btn-primary">Calcular Frete</button>
                        </form>

                        @if (session()->has('error'))
                            <p style="color: red; margin-top: 10px;">{{ session('error') }}</p>
                        @endif

                        @if (session()->has('valorFrete') && session()->has('prazoFrete'))
                            <div id="resultado-frete" class="mt-3">
                                <p><strong>Frete:</strong> R$ {{ session('valorFrete') }}</p>
                                <p><strong>Prazo de Entrega:</strong> {{ session('prazoFrete') }} dias úteis</p>
                            </div>
                        @endif



                        <li class="list-group-item">
                            {{-- <form action="{{ route('pedidos.aplicar-cupom', ['pedidoId' => $pedido->id]) }}" method="POST">
                                @csrf --}}
                            <div class="input-group">
                                <input type="text" name="codigo" id="codigo-cupom"
                                    placeholder="Digite o código do cupom" required class="form-control">
                                <button type="button" class="btn btn-success" id="aplicar-cupom">Aplicar</button>
                            </div>
                            {{-- </form> --}}
                        </li>

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <li class="list-group-item">
                            <h5>Total:
                                <span class="valor-total">
                                    R$
                                    {{ number_format($pedido->total_com_desconto, 2, ',', '.') }}
                                </span>

                            </h5>
                        </li>

                    </ul>
                </div>

                <!-- Checkout -->
                <form action="{{ route('checkout') }}" method="POST">
                    @csrf
                    <input type="hidden" name="pedidoId" value="{{ $pedido->id }}">
                    <input type="hidden" name="valor_total" id="valor-total-input">
                    <input type="hidden" name="codigoCupom" id="codigo-cupom-input">
                    <button type="submit" class="btn btn-success btn-lg btn-block">Ir para o Checkout</button>
                </form>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- iziToast CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/css/iziToast.min.css">

        <!-- iziToast JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
        <script>
            $('#aplicar-cupom').click(function() {
                console.log('apliquei');
                aplicarCupom();
            });
            function aplicarCupom() {
                let id = {{ $pedido->id }};
                let codigoCupom = $('#codigo-cupom').val();
                if (codigoCupom !== "") {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('pedidos.aplicar-cupom') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            pedidoId: id,
                            codigoCupom: codigoCupom
                        },
                        success: function(response) {
                            if (!response.error) {
                                $('.valor-total').text(formatarDinheiro(response));
                                $('#valor-total-input').val(response);
                                $('#codigo-cupom-input').val(codigoCupom);
                                iziToast.success({
                                    title: 'Sucesso!',
                                    message: "Cupom aplicado",
                                    position: 'topRight'
                                });
                                return;
                            }
                            $('#valor-total-input').val(response.results);
                            $('#codigo-cupom-input').val(null);

                            iziToast.error({
                                title: 'Atenção!',
                                message: response.msg,
                                position: 'topRight'
                            });
                            $('.valor-total').text(formatarDinheiro(response.results));

                        }
                    });
                }

            }

            function formatarDinheiro(valor) {
                return new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(valor);
            }
        </script>
    @endsection
