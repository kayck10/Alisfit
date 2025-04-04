<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Alis')</title>
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

        /* Ajuste a posição do offcanvas */
        .offcanvas-carrinho.offcanvas-end {
            top: 0;
            height: auto;
            background-color: #1c1c1c;
            /* Fundo preto */
            color: white;
            /* Texto branco */
        }

        /* Header do offcanvas */
        .offcanvas-header-carrinho {
            background-color: #2c2c2c;
            /* Cinza escuro */
            color: white;
            border-bottom: 2px solid #444;
        }

        /* Linha divisória */
        .hr {
            border-color: #444;
        }

        /* Corpo do offcanvas */
        .offcanvas-body-carrinho {
            background-color: #1c1c1c;
        }

        /* Estilização da lista de produtos */
        .list-group-item-carrinho {
            background-color: #2c2c2c;
            /* Cinza escuro */
            color: white;
            border: 1px solid #444;
            /* Bordas sutis */
        }

        /* Inputs de quantidade */
        .update-quantity-carrinho {
            background-color: #333;
            color: white;
            border: 1px solid #555;
        }

        /* Botões de adicionar e remover quantidade */
        .update-quantity-btn-carrinho {
            background-color: #444;
            color: white;
            border: none;
        }

        .update-quantity-btn:hover {
            background-color: #555;
        }

        /* Botão de excluir */
        .btn-transparent {
            color: white;
        }

        .btn-transparent:hover {
            color: #ff4d4d;
        }

        /* Barra de progresso */
        .progress {
            background-color: #444;
        }

        .progress-bar {
            background-color: #28a745;
        }

        /* Rodapé */
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
                            <li><a href="{{ route('produtos.ofertasM') }}">Ofertas Masculinas</a></li>
                            <li><a href="{{ route('produtos.ofertasF') }}">Ofertas Femininas</a></li>
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
                        aria-controls="menuOffcanvas" class="d-block d-sm-none">
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
                            $freteGratisValor = 200;
                        @endphp
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

                    <!-- Barra de progresso do frete grátis -->
                    @php
                        $valorFaltante = max(0, $freteGratisValor - $totalCarrinho);
                        $percentual = min(100, ($totalCarrinho / $freteGratisValor) * 100);
                    @endphp

                    <div class="mt-3">
                        @if ($totalCarrinho >= $freteGratisValor)
                            <p class="text-success fw-bold">🎉 Parabéns! Você ganhou frete grátis!</p>
                        @else
                            <p class="text-danger">Faltam <strong>R$
                                    {{ number_format($valorFaltante, 2, ',', '.') }}</strong> para você ganhar frete
                                grátis.</p>
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar"
                                    style="width: {{ $percentual }}%;" aria-valuenow="{{ $percentual }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($percentual, 0) }}%
                                </div>
                            </div>
                        @endif
                    </div>

                    <hr>

                    <h5 class="text-end"><strong>Total: R$ <span
                                id="totalCarrinho">{{ number_format($totalCarrinho, 2, ',', '.') }}</span></strong>
                    </h5>

                @endif
            </div>

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
                <!-- Logo -->
                <div class="text-center mb-3">
                    <a href="{{ route('loja.create') }}">
                        <img src="{{ asset('images/ALis - nova logo-03.png') }}" class="img-fluid" alt="Logo"
                            style="max-width: 150px;">
                    </a>
                </div>

                <!-- Menu -->
                <ul class="list-unstyled">
                    <li><a href="{{ route('drops') }}" class="d-block text-decoration-none">LANÇAMENTOS</a></li>

                    <!-- Dropdown Masculino -->
                    <li>
                        <a href="#masculinoMenu" class="d-block text-decoration-none"
                            data-bs-toggle="collapse">MASCULINO ▼</a>
                        <ul id="masculinoMenu" class="collapse ps-3">
                            <li><a href="{{ route('produtos.masculinasCamisetas') }}"
                                    class="d-block text-decoration-none">Camisas</a></li>
                            <li><a href="{{ route('produtos.masculinosShorts') }}"
                                    class="d-block text-decoration-none">Shorts</a></li>
                        </ul>
                    </li>

                    <!-- Dropdown Feminino -->
                    <li>
                        <a href="#femininoMenu" class="d-block text-decoration-none"
                            data-bs-toggle="collapse">FEMININO ▼</a>
                        <ul id="femininoMenu" class="collapse ps-3">
                            <li><a href="{{ route('produtos.femininosTops') }}"
                                    class="d-block text-decoration-none">Tops</a></li>
                            <li><a href="{{ route('produtos.femininosLegging') }}"
                                    class="d-block text-decoration-none">Leggings</a></li>
                            <li><a href="{{ route('produtos.femininosShorts') }}"
                                    class="d-block text-decoration-none">Shorts</a></li>
                        </ul>
                    </li>

                    <!-- Dropdown Ofertas -->
                    <li>
                        <a href="#ofertasMenu" class="d-block text-decoration-none" data-bs-toggle="collapse">OFERTAS
                            ▼</a>
                        <ul id="ofertasMenu" class="collapse ps-3">
                            <li><a href="{{route('produtos.ofertasM')}}" class="d-block text-decoration-none">Ofertas Masculinas</a></li>
                            <li><a href="{{route('produtos.ofertasF')}}" class="d-block text-decoration-none">Ofertas Femininas</a></li>
                        </ul>
                    </li>

                    <!-- Dropdown Coleções -->
                    <li>
                        <a href="#colecoesMenu" class="d-block text-decoration-none" data-bs-toggle="collapse">DROPS
                            ▼</a>
                        <ul id="colecoesMenu" class="collapse ps-3">
                            @foreach ($colecoes as $colecao)
                                <li><a href="{{ route('colecoes.show.loja', $colecao->id) }}"
                                        class="d-block text-decoration-none">{{ $colecao->nome }}</a></li>
                            @endforeach
                        </ul>
                    </li>

                    <li><a href="{{ route('loja.sobre') }}" class="d-block text-decoration-none">SOBRE NÓS</a></li>
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
                    <form id="registerForm" action="{{ route('cliente.cadastro') }}" method="POST"
                        style="display: none;">
                        @csrf
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" required
                                autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="emailCadastro" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="emailCadastro" name="email" required
                                autocomplete="off">
                        </div>
                        <div class="mb-3">
                            <label for="senhaCadastro" class="form-label">Senha</label>
                            <input type="password" class="form-control" id="senhaCadastro" name="senha" required
                                autocomplete="new-password">
                        </div>
                        <div class="mb-3">
                            <label for="senhaConfirm" class="form-label">Confirmar Senha</label>
                            <input type="password" class="form-control" id="senhaConfirm" name="senha_confirmation"
                                required autocomplete="new-password">
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

    function atualizarBarraFreteGratis(totalCarrinho) {
        let valorFreteGratis = 200;
        let faltando = Math.max(0, valorFreteGratis - totalCarrinho);
        let percentual = Math.min(100, (totalCarrinho / valorFreteGratis) * 100);

        $(".progress-bar").css("width", percentual + "%").attr("aria-valuenow", percentual);

        if (faltando > 0) {
            $(".progress").prev("p").html(
                `Faltam <strong>R$ ${faltando.toFixed(2).replace('.', ',')}</strong> para frete grátis!`);
        } else {
            $(".progress").prev("p").html("🎉 Parabéns! Você tem frete grátis!");
        }
    }

    // Chamar a função sempre que atualizar o carrinho
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
                    let produtoData = data.produtos.find(produto => produto.pivot.produto_id == produtoId);
                    let subtotal = produtoData.pivot.quantidade * produtoData.preco;

                    produtoElement.find('.me-auto small:last-child').text(
                        `Total: R$ ${subtotal.toFixed(2).replace('.', ',')}`
                    );

                    $(".offcanvas-body h5 strong").text(`Total: R$ ${data.total}`);

                    atualizarBarraFreteGratis(data.total);
                } else {
                    alert(data.error);
                }
            },
            error: function(error) {
                console.error('Erro ao atualizar o carrinho:', error);
            }
        });
    }
</script>

<script>
    $(document).ready(function() {
        // Busca endereço via CEP
        $("#calcularFrete").click(function() {
            var cep = $("#cepCalcular").val().replace(/\D/g, '');

            if (cep.length !== 8) {
                alert("CEP inválido");
                return;
            }

            $.getJSON("https://viacep.com.br/ws/" + cep + "/json/", function(data) {
                if (data.erro) {
                    alert("CEP não encontrado");
                } else {
                    $("#rua").val(data.logradouro);
                    $("#bairro").val(data.bairro);
                    $("#cidade").val(data.localidade);
                    $("#estado").val(data.uf);
                    $("#cep").val(cep.replace(/(\d{5})(\d{3})/, "$1-$2"));

                    // Mostra o formulário de endereço
                    $("#enderecoEntrega").removeClass("hide").slideDown();

                    // Calcula o frete (se necessário)
                    calcularFrete(cep);
                }
            }).fail(function() {
                alert("Erro ao buscar CEP");
            });
        });

        // Finalizar pedido e redirecionar para pagamento
        $("#finalizarPedido").click(function() {
            // Validação básica
            if (!$("#numero").val()) {
                alert("Por favor, informe o número do endereço");
                return;
            }

            // Mostrar loading (opcional)
            $(this).html('<i class="fas fa-spinner fa-spin"></i> Processando...').prop('disabled',
                true);

            // Coletar todos os dados
            var dadosPedido = {
                _token: "{{ csrf_token() }}",
                rua: $("#rua").val(),
                numero: $("#numero").val(),
                bairro: $("#bairro").val(),
                cidade: $("#cidade").val(),
                estado: $("#estado").val(),
                cep: $("#cep").val().replace(/\D/g, ''),
                complemento: $("#complemento").val(),
                valorFrete: parseFloat("{{ session('valorFrete', 0) }}"),
                codigoCupom: "{{ session('cupom.codigo') }}"
            };

            // Enviar para o servidor
            $.ajax({
                url: "{{ route('carrinho.finalizarPedido') }}",
                method: "POST",
                data: dadosPedido,
                dataType: "json",
                success: function(response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        alert("Erro ao processar pedido");
                    }
                },
                error: function(xhr) {
                    var errorMsg = xhr.responseJSON?.message || "Erro ao finalizar pedido";
                    alert(errorMsg);
                    $("#finalizarPedido").html(
                        '<i class="fas fa-check-circle"></i> Finalizar e Pagar').prop(
                        'disabled', false);
                }
            });
        });

        // Função para calcular frete (se necessário)
        function calcularFrete(cep) {
            $.get("{{ route('frete.calcular') }}", {
                cep: cep
            }, function(data) {
                if (data.erro) {
                    alert(data.erro);
                } else {
                    var valorFrete = parseFloat(data.valor);
                    var prazo = data.prazo;

                    $("#infoFrete").html(`
                <li class="list-group-item">Frete: <strong>R$ ${valorFrete.toFixed(2).replace(".", ",")}</strong></li>
                <li class="list-group-item">Prazo: <strong>${prazo} dias úteis</strong></li>
            `);

                    // Atualiza o total com o frete
                    var subtotal = parseFloat($("#totalPedido").data("total"));
                    var desconto = parseFloat("{{ $desconto ?? 0 }}");
                    var total = (subtotal + valorFrete) - desconto;

                    $("#totalPedido").html(`Total: R$ ${total.toFixed(2).replace(".", ",")}`);
                }
            }, "json");
        }
    });
</script>

</html>
