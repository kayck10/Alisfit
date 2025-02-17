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
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
</head>

<body>
    {{-- Cabeçalho --}}
    <section id="header">
        <div class="logo-container">
            <a href="{{ route('produtos.index') }}">
                <img src="{{ asset('images/ALis - nova logo-03.png') }}" class="logo" alt="Logo">
            </a>
        </div>

        <nav class="nav-menu">
            <ul id="navbar">
                <li><a href="{{ route('drops') }}">LANÇAMENTOS</a></li>

                <li>
                    <a href="#">MASCULINO</a>
                    <div class="dropdown">
                        <h4>ROUPAS MASCULINAS</h4>
                        <ul>
                            <li><a href="">Camisas</a></li>
                            <li><a href="">Shorts</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="#">FEMININO</a>
                    <div class="dropdown">
                        <h4>ROUPAS FEMININAS</h4>
                        <ul>
                            <li><a href="">Tops</a></li>
                            <li><a href="">Leggings</a></li>
                            <li><a href="">Shorts</a></li>
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
                    <a href="#">COLEÇÕES</a>
                    <div class="dropdown">
                        <h4>DROPS</h4>
                        <ul>
                            <li><a href="">Volans</a></li>
                            <li><a href="">Satus</a></li>
                        </ul>
                    </div>
                </li>
                <li><a href="#">SOBRE NÓS</a></li>
            </ul>
        </nav>

        <div class="nav-extra">
            <ul id="navbar-icons">
                <li id="search-icon"><a href="#"><i class="bi bi-search"></i></a></li>
                <!-- Ícone do Carrinho -->
                <li id="cart-icon-container">
                    <a href="#" id="cart-icon" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <i class="bi bi-bag-fill"></i>
                    </a>
                    <span id="cart-count">0</span>
                </li>
                <li id="user-icon" class="user-icon-container">
                    <a href="#" id="open-login-modal"><i class="bi bi-person-fill"></i></a>
                    <div class="user-menu">
                        {{-- <ul>
                            <li><a href="">Minha Conta</a></li>
                            <li><a href="">Meus pedidos</a></li>
                            <li><a href="">Lista de Desejos <i class="fa-solid fa-heart"></i></a></li>
                            <li><a href="" id="logout-btn">Sair <i class="fa-solid fa-right-from-bracket"></i></a></li>
                        </ul> --}}
                    </div>
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
                                <!-- Input de quantidade -->
                                <form action="{{ route('carrinho.atualizar', $produto->pivot->produto_id) }}"
                                    method="POST" class="update-quantity-form">
                                    @csrf
                                    <input type="number" name="quantidade"
                                        class="form-control form-control-sm text-center mx-2 update-quantity"
                                        value="{{ $produto->pivot->quantidade }}" min="1" style="width: 60px;">
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
                    </div>

                    <h5 class="text-end"><strong>Total: R$ {{ number_format($totalCarrinho, 2, ',', '.') }}</strong>
                    </h5>
                @endif
            </div>
            <!-- Botão Finalizar Compra fixo no fundo -->
            @if (isset($carrinho) && !$carrinho->produtos->isEmpty())
                <div class="p-3 border-top bg-light">
                    <a href="" class="btn btn-dark w-100">Finalizar Compra</a>
                </div>
            @endif
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
                <a href="#">{{ __('Sobre nós') }}</a>
                <a href="#">{{ __('Informações de entrega') }}</a>
                <a href="#">{{ __('Política de Privacidade') }}</a>
                <a href="#">{{ __('Termos & Condições') }}</a>
                <a href="#">{{ __('Fale Conosco') }}</a>
            </div>

            <div class="col">
                <h4>{{ __('Minha Conta') }}</h4>
                <a href="#">{{ __('Entrar') }}</a>
                <a href="#">{{ __('Ver Carrinho') }}</a>
                <a href="#">{{ __('Minha Lista de Desejos') }}</a>
                <a href="#">{{ __('Rastrear Pedido') }}</a>
                <a href="#">{{ __('Ajuda') }}</a>
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
        </div>

        <div id="copyright">
            <p>{{ __('CNPJ "XXXXXXXX" - © 2024 Alis - Todos os direitos reservados.') }}</p>
            <p>{{ __('Desenvolvido por João Lucas Paiva') }}</p>
        </div>
    </footer>

    <div id="page-overlay"></div>

    <div id="cart-sidebar" class="cart-sidebar">
        <div class="cart-sidebar-header">
            <h3>{{ __('CARRINHO') }}</h3>
            <button id="close-cart" class="close-cart-btn">&times;</button>
        </div>

        <div id="free-shipping-container">
            <p id="free-shipping-text">{{ __('Frete grátis a partir de R$290,00') }}</p>
            <div id="progress-bar-container">
                <div id="progress-bar"></div>
            </div>
        </div>

        <div id="cart-items-container"></div>

        <div id="empty-cart-message" style="display:none;">
            <p class="empty-cart-text">{{ __('Seu Carrinho está vazio!') }}</p>
            <p class="empty-cart-subtext">{{ __('Clique no botão abaixo e procure pelo seu produto!') }}</p>
            <button id="find-products-btn" class="find-products-btn">{{ __('PROCURAR') }}</button>
        </div>

        <div class="cart-sidebar-footer">
            <p id="cart-subtotal">{{ __('Subtotal: R$0,00') }}</p>
            <button id="checkout-btn" class="checkout-btn">{{ __('Finalizar Compra') }}</button>
        </div>
    </div>

    <div id="modal-overlay"></div>

    <div id="login-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" id="closeLogin">&times;</span>
            <h2>{{ __('Entrar na Conta') }}</h2>
            <button class="login-btn google-login">
                <img src="{{ asset('img/pay/google-icon.png') }}" alt="Google Icon" class="icon" />
                {{ __('Entre com Google') }}
            </button>
            <button class="login-btn facebook-login">
                <img src="{{ asset('img/pay/facebook-icon.png') }}" alt="Facebook Icon" class="icon" />
                {{ __('Entre com Facebook') }}
            </button>
            <div class="divider">
                <hr> <span>{{ __('ou') }}</span>
                <hr>
            </div>
            <form id="login-form">
                <input type="email" id="login-email" placeholder="{{ __('E-mail') }}" required
                    autocomplete="off">
                <div class="password-container-toggle">
                    <input type="password" id="login-password" placeholder="{{ __('Senha') }}" required
                        autocomplete="off">
                    <i class="fa-solid fa-eye-slash toggle-password" id="toggle-login-password"></i>
                </div>
                <a href="#">{{ __('Esqueceu a senha?') }}</a>
                <button type="submit" class="submit-btn" id="login-btn">
                    {{ __('Fazer login') }}
                </button>
            </form>
            <p>{{ __('Não tem uma conta?') }} <a href="#" id="showRegisterModal"
                    class="create-account">{{ __('Criar conta') }}</a></p>
        </div>
    </div>

    <div id="register-modal" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close-btn" id="closeRegister">&times;</span>
            <h2>{{ __('Criar Conta') }}</h2>
            <button class="login-btn google-login">
                <img src="{{ asset('img/pay/google-icon.png') }}" alt="Google Icon" class="icon" />
                {{ __('Registre-se com Google') }}
            </button>
            <button class="login-btn facebook-login">
                <img src="{{ asset('img/pay/facebook-icon.png') }}" alt="Facebook Icon" class="icon" />
                {{ __('Registre-se com Facebook') }}
            </button>
            <form id="register-form">
                <input type="text" id="register-name" placeholder="{{ __('Nome de Usuário') }}" required
                    autocomplete="off">
                <input type="email" id="register-email" placeholder="{{ __('E-mail') }}" required
                    autocomplete="off">

                <div class="password-container">
                    <div class="password-wrapper">
                        <div class="password-container-toggle">
                            <input type="password" id="register-password" placeholder="{{ __('Senha') }}"
                                required>
                            <i class="fa-solid fa-eye-slash toggle-password" id="toggle-register-password"></i>
                        </div>
                        <div class="password-container-toggle">
                            <input type="password" id="confirm-password" placeholder="{{ __('Confirme a Senha') }}"
                                required>
                            <i class="fa-solid fa-eye-slash toggle-password" id="toggle-confirm-password"></i>
                        </div>
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="register-btn">
                    {{ __('Registrar') }}
                </button>
            </form>
            <p class="create-account-r">{{ __('Já tem uma conta?') }} <a href="#"
                    id="showLoginModal">{{ __('Faça login') }}</a></p>
        </div>

        <script src="{{ asset('js/script.js') }}"></script>
</body>
<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
{{-- {!! Toastr::message() !!} --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const header = document.getElementById("header");

        function toggleNavbarBackground() {
            if (window.scrollY === 0) {
                header.classList.remove("scrolled");
            } else {
                header.classList.add("scrolled");
            }
        }

        window.addEventListener("scroll", toggleNavbarBackground);
        toggleNavbarBackground();
    });

    document.getElementById('btnCupom').addEventListener('click', function() {
        this.classList.add('d-none');
        document.getElementById('inputCupom').classList.remove('d-none');
    });
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".update-quantity").forEach(input => {
            input.addEventListener("change", function() {
                this.closest(".update-quantity-form").submit();
            });
        });
    });
</script>

</html>
