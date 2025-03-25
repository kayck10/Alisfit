<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Alis - Moda Fitness')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <style>
        .hide {
            display: none;
        }
    </style>
</head>

<style>

</style>

<body>
    {{-- Cabeçalho --}}
    <section id="header">
        <div class="logo-container">
            <a href="{{ route('loja.create') }}">
                <img src="{{ asset('images/ALis - nova logo-03.png') }}" class="logo" alt="Logo">
            </a>
        </div>

        <nav class="nav-menu mt-2">
            <ul id="navbar">
                <li><a href="{{ route('drops') }}">LANÇAMENTOS</a></li>

                <li>
                    <a href="{{ route('produtos.masculinos') }}">MASCULINO</a>
                    <div class="dropdown">
                        <h4>ROUPAS MASCULINAS</h4>
                        <ul>
                            <li><a href="{{ route('produtos.masculinasCamisetas') }}">Camisas</a></li>
                            <li><a href="{{ route('produtos.masculinosShorts') }}">Shorts</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="{{ route('produtos.femininos') }}">FEMININO</a>
                    <div class="dropdown">
                        <h4>ROUPAS FEMININAS</h4>
                        <ul>
                            <li><a href="{{ route('produtos.femininosTops') }}">Tops</a></li>
                            <li><a href="{{ route('produtos.femininosLegging') }}">Leggings</a></li>
                            <li><a href="{{ route('produtos.femininosShorts') }}">Shorts</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#">OFERTAS</a>
                    <div class="dropdown">
                        <ul>
                            <li><a href="">Ofertas Masculinas</a></li>
                            <li><a href="">Ofertas Femininas</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="">COLEÇÕES</a>
                    <div class="dropdown">
                        <h4>DROPS</h4>
                        <ul>
                            @foreach ($colecoes as $colecao)
                                <li><a href="{{ route('colecoes.show.loja', $colecao->id) }}">{{ $colecao->nome }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>

                <li><a href="{{ route('loja.sobre') }}">SOBRE NÓS</a></li>
            </ul>
        </nav>

        <div class="nav-extra">
            <ul id="navbar-icons">
                <!-- Ícone do Menu (Visível apenas em telas menores) -->
                <li id="search-icon" class="user-icon-container">
                    <a href="#" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuOffcanvas"
                        aria-controls="menuOffcanvas">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </li>
                <!-- Ícone do Carrinho -->
                <li id="cart-icon-container">
                    <a href="#" id="cart-icon" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <i class="bi bi-bag-fill"></i>
                    </a>
                    <span id="cart-count">0</span>
                </li>
                <li id="user-icon" class="user-icon-container">
                    @if (Auth::check())
                        <!-- Usuário logado: Mostrar dropdown -->
                        <div class="user-icon-container">
                            <a href="#" id="user-dropdown-toggle">
                                <i class="bi bi-person-fill"></i>
                            </a>
                            <div id="user-menu">
                                <ul>
                                    <li><a href="{{ route('conta') }}">Minha Conta</a></li>
                                    <li><a href="#">Meus Pedidos</a></li>
                                    <li><a href="#">Lista de Desejos <i class="fa-solid fa-heart"></i></a></li>
                                    <li><a href="#" id="logout-btn">Sair <i
                                                class="fa-solid fa-right-from-bracket"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    @else
                        <!-- Usuário não logado: Mostrar modal de login -->
                        <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="bi bi-person-fill"></i>
                        </a>
                    @endif
                </li>


            </ul>
        </div>

        <!-- Offcanvas do Carrinho -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas" aria-labelledby="cartOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="mt-2" id="cartOffcanvasLabel"><strong>Carrinho</strong></h5>
                <hr class="hr">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
            </div>

            <div class="offcanvas-body d-flex flex-column">
                @if (!isset($carrinho) || $carrinho->produtos->isEmpty())
                    <p>Seu carrinho está vazio.</p>
                @else
                    <ul class="list-group flex-grow-1">
                        @php
                            $totalCarrinho = 0;
                        @endphp
                        @foreach ($carrinho->produtos as $produto)
                            @php
                                $subtotal = $produto->preco * $produto->pivot->quantidade;
                                $totalCarrinho += $subtotal;
                            @endphp
                            <li class="list-group-item d-flex align-items-center">
                                <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}"
                                    alt="{{ $produto->nome }}" class="img-fluid rounded"
                                    style="width: 70px; height: 70px; object-fit: cover; margin-right: 10px;">
                                <div class="me-auto">
                                    <strong>{{ $produto->nome }}</strong> <br>
                                    <small>R$ {{ number_format($produto->preco, 2, ',', '.') }}</small> <br>
                                    <small><strong>Total:</strong> R$
                                        {{ number_format($subtotal, 2, ',', '.') }}</small>
                                </div>
                                <form class="update-quantity-form d-flex align-items-center"
                                    data-produto-id="{{ $produto->pivot->produto_id }}">
                                    @csrf
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary update-quantity-btn"
                                        data-action="decrease">-</button>
                                    <input type="number" name="quantidade"
                                        class="form-control form-control-sm text-center mx-2 update-quantity"
                                        value="{{ $produto->pivot->quantidade }}" min="1"
                                        style="width: 60px;">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-secondary update-quantity-btn"
                                        data-action="increase">+</button>
                                </form>


                                <form action="{{ route('carrinho.remover', $produto->pivot->produto_id) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-transparent btn-sm">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </li>
                        @endforeach

                    </ul>

                    <hr>

                    <!-- Botão de cupom -->
                    <div class="mb-3 text-center">
                        <button id="btnCupom" class="btn btn-outline-secondary btn-sm">Adicionar Cupom</button>
                        <input type="text" id="inputCupom" class="form-control d-none mt-2"
                            placeholder="Digite seu cupom">
                        <button id="applyCupom" class="btn btn-primary d-none mt-2">Aplicar</button>
                    </div>
                    <!-- Input hidden para armazenar o ID do pedido -->
                    <input type="hidden" id="pedidoId" name="pedido_id" value="{{ $pedido ?? '' }}">

                    <h5 class="text-end"><strong>Total: R$ {{ number_format($totalCarrinho, 2, ',', '.') }}</strong>
                    </h5>

                @endif
            </div>
            <!-- Botão Finalizar Compra fixo no fundo -->
            @if (isset($carrinho) && !$carrinho->produtos->isEmpty())
                <div class="p-3 border-top bg-light">
                    <a href="{{ route('carrinho.informacoes') }}" class="btn btn-dark w-100">Finalizar Compra</a>
                </div>
            @endif
        </div>

        <!-- Offcanvas Menu -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="menuOffcanvas"
            aria-labelledby="menuOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="mt-2" id="menuOffcanvasLabel"><strong>Menu</strong></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Fechar"></button>
            </div>
            <div class="offcanvas-body">
                <ul id="menu-list">
                    <li><a href="{{ route('drops') }}">LANÇAMENTOS</a></li>
                    <li><a href="#">MASCULINO</a></li>
                    <li><a href="#">FEMININO</a></li>
                    <li><a href="#">OFERTAS</a></li>
                    <li><a href="#">COLEÇÕES</a></li>
                    <li><a href="#">SOBRE NÓS</a></li>
                </ul>
            </div>
        </div>
    </section>



    {{-- Conteúdo dinâmico da página --}}
    <main>
        @yield('content')
    </main>

    {{-- Rodapé --}}
    <footer>
        <div class="bottom-footer">
            <div class="col">
                <h4>{{ __('Contato') }}</h4>
                <p><strong>{{ __('E-mail:') }}</strong> alisoriginalfitness@gmail.com</p>
                <p><strong>{{ __('Whatsapp:') }}</strong> +55 (85) 92001-4169</p>
                <p><strong>{{ __('Horário de atendimento:') }}</strong> {{ __('de segunda à sábado') }} <br>
                    08:00-18:00
                </p>
            </div>

            <div class="col">
                <h4>{{ __('Dúvidas') }}</h4>
                <a href="{{ route('loja.sobre') }}">{{ __('Sobre nós') }}</a>
                <a href="{{ route('informacoes') }}">{{ __('Informações de entrega') }}</a>
                <a href="{{ route('informacoes') }}">{{ __('Política de Privacidade') }}</a>
                <a href="{{ route('informacoes') }}">{{ __('Termos & Condições') }}</a>
                <a href="{{ route('informacoes') }}">{{ __('Fale Conosco') }}</a>
            </div>

            <div class="col">
                <h4>{{ __('Formas de Pagamento') }}</h4>
                <div class="pay-icons">
                    <img class="visa-img" src="{{ asset('images/pay/visa-logo.png') }}" alt="Visa">
                    <img class="pix-img" src="{{ asset('images/pay/logo-pix.png') }}" alt="Pix">
                    <img class="mastercard-img" src="{{ asset('images/pay/mastercard-logo.png') }}"
                        alt="Mastercard">
                    <img class="paypal-img" src="{{ asset('images/pay/paypal-logo.png') }}" alt="Paypal">
                </div>
            </div>
            <div class="col">
                <h4>{{ __() }}</h4>
                <div class="pay-icons-alis">
                    <img src="{{ asset('images/banner/alis - frase vectorizada_Prancheta 1 cópia (1).png') }}"
                        alt="Banner">

                </div>
            </div>
        </div>



        <div id="copyright">
            <p>{{ __('CNPJ "XXXXXXXX" - © 2024 Alis - Todos os direitos reservados.') }}</p>
            <p>{{ __('Desenvolvido por Tarcísio Kayck') }}</p>
        </div>

    </footer>

    <!-- Modal de Login -->
    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loginModalLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulário de Login -->
                    <form id="loginForm" action="{{ route('cliente.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id_tipos_usuarios" value="3">
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    </form>

                    <div class="text-center mt-3">
                        <small>Não tem uma conta? <a href="#" id="showRegister">Crie uma agora</a></small>
                    </div>

                    <!-- Formulário de Cadastro -->
                    <form id="registerForm" action="{{ route('cliente.cadastro') }}" method="POST" style="display: none;">
                        @csrf
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="emailCadastro" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="emailCadastro" name="email" required autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="senhaCadastro" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senhaCadastro" name="senha" required autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="senhaConfirm" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="senhaConfirm" name="senha_confirmation" required autocomplete="new-password">
                        </div>
                        <button type="submit" class="btn btn-success w-100">Criar Conta</button>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

</body>
<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
{!! Toastr::message() !!}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

@yield('scripts')

<script>
    $(document).ready(function() {
        // Alternar entre login e cadastro
        $('#showRegister').click(function() {
            $('#loginForm').hide();
            $('#registerForm').show();
        });

        $('#showLogin').click(function() {
            $('#registerForm').hide();
            $('#loginForm').show();
        });

        // Dropdown do usuário
        const userDropdownToggle = $('#user-dropdown-toggle');
        const userMenu = $('.user-menu');

        if (userDropdownToggle.length) {
            userDropdownToggle.click(function(event) {
                event.preventDefault();
                userMenu.toggleClass('d-none');
            });

            $(document).click(function(event) {
                if (!userDropdownToggle.is(event.target) && !userMenu.is(event.target) && userMenu.has(
                        event.target).length === 0) {
                    userMenu.addClass('d-none');
                }
            });
        }

        // Abrir modal de login
        $('#open-login-modal').click(function(event) {
            event.preventDefault();
            let loginModal = new bootstrap.Modal($('#loginModal'));
            loginModal.show();
        });

        // Navbar - alterar fundo ao rolar a página
        const header = $("#header");

        function toggleNavbarBackground() {
            header.toggleClass("scrolled", $(window).scrollTop() > 0);
        }

        $(window).scroll(toggleNavbarBackground);
        toggleNavbarBackground();

        $('#nav-toggle').click(function() {
            $('#navbar').toggleClass('active');
        });

        // Cupom de desconto
        $('#btnCupom').click(function() {
            $(this).addClass('d-none');
            $('#inputCupom, #applyCupom').removeClass('d-none');
        });


        // Atualizar quantidade no carrinho
        $('.update-quantity').change(function() {
            atualizarQuantidade($(this));
        });

        $('.update-quantity-btn').click(function() {
            let input = $(this).closest('.update-quantity-form').find('.update-quantity');
            let currentValue = parseInt(input.val()) || 1;
            let action = $(this).data('action');

            if (action === "increase") {
                input.val(currentValue + 1);
            } else if (action === "decrease" && currentValue > 1) {
                input.val(currentValue - 1);
            }

            atualizarQuantidade(input);
        });

        function atualizarQuantidade(input) {
            let form = input.closest(".update-quantity-form");
            let produtoId = form.data("produto-id");
            let quantidade = input.val();

            $.ajax({
                url: `/carrinho/atualizar/${produtoId}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: new FormData(form[0]),
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        let produtoElement = form.closest('li');
                        let produtoData = data.produtos.find(produto => produto.pivot.produto_id ==
                            produtoId);
                        let subtotal = produtoData.pivot.quantidade * produtoData.preco;

                        produtoElement.find('.me-auto small:last-child').text(
                            `Total: R$ ${subtotal.toFixed(2).replace('.', ',')}`);
                        $(".offcanvas-body h5 strong").text(`Total: R$ ${data.total}`);
                    } else {
                        alert(data.error);
                    }
                },
                error: function(error) {
                    console.error('Erro ao atualizar o carrinho:', error);
                }
            });
        }

        // Remover produto do carrinho
        $(".remover-produto").click(function(e) {
            e.preventDefault();
            $(this).closest("form").submit();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $("#calcularFrete").click(function() {
            var cep = $("#cepCalcular").val();
            $.ajax({
                url: "https://viacep.com.br/ws/" + cep + "/json/",
                method: "GET",
                dataType: "json",
                success: function(data) {
                    if (data.erro) {
                        alert("CEP inválido");
                    } else {
                        $("#rua").val(data.logradouro);
                        $("#bairro").val(data.bairro);
                        $("#cidade").val(data.localidade);
                        $("#estado").val(data.uf);
                        $("#cep").val(cep);
                        $("#enderecoEntrega").removeClass("hide").slideDown();
                        calcularFrete(cep);
                    }
                },
                error: function() {
                    alert("Erro ao calcular frete");
                }
            })
        });
    })

    const calcularFrete = (cep) => {
        $.ajax({
            url: "{{ route('frete.calcular') }}",
            method: "GET",
            data: {
                cep: cep
            },
            dataType: "json",
            success: function(data) {
                if (data.erro) {
                    alert("CEP inválido");
                } else {
                    var total = $("#totalPedido").data("total");
                    var descontos = $("#totalPedido").data("descontos");
                    $("#infoFrete").html(`
                        <li class="list-group-item">Frete: <strong class="">R$ ${data.valor} </strong></li>
                        <li class="list-group-item">Prazo: <strong class=""><span id="prazoEntrega">${data.prazo}</span> dias úteis</strong></li>
                    `);


                    $("#totalPedido").html(`Total: R$ ${(total + parseFloat(data.valor)).toFixed(2)}`);
                }
            },
            error: function() {
                alert("Erro ao calcular frete");
            }
        })
    }

    $(document).on('submit', 'form[action="{{ route('carrinho.aplicar-cupom') }}"]', function(e) {
        e.preventDefault();

        var formData = $(this).serialize();
        var url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.error) {
                    alert(response.msg); // Exibe erro, caso ocorra
                } else {
                    $('#total').html(`<strong>Total:</strong> R$ ` + response.novoTotal.toFixed(2));
                    $('#desconto').html(`<strong>Desconto Aplicado:</strong> R$ ` + response
                        .desconto.toFixed(2));

                    alert(respons1e.msg);
                }
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    // Erro de validação
                    alert('Erro de validação: ' + JSON.stringify(xhr.responseJSON.errors));
                } else if (xhr.status === 500) {
                    alert('Erro interno no servidor. Verifique o log para mais detalhes.');
                } else {
                    alert('Erro ao aplicar o cupom.');
                }
            }
        });
    });
</script>

</html>
