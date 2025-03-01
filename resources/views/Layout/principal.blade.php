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
                    <a href="{{route('produtos.masculinos')}}">MASCULINO</a>
                    <div class="dropdown">
                        <h4>ROUPAS MASCULINAS</h4>
                        <ul>
                            <li><a href="">Camisas</a></li>
                            <li><a href="">Shorts</a></li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="{{route('produtos.femininos')}}">FEMININO</a>
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

                <li><a href="#">SOBRE NÓS</a></li>
            </ul>
        </nav>

        <div class="nav-extra">
            <ul id="navbar-icons">
                <!-- Ícone do Menu (Visível apenas em telas menores) -->
                <li id="menu-toggle" class="user-icon-container">
                    <a href="#" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuOffcanvas"
                        aria-controls="menuOffcanvas">
                        <i class="fa-solid fa-bars"></i>
                    </a>
                </li>
                <li id="search-icon"><a href="#"><i class="bi bi-search"></i></a></li>
                <!-- Ícone do Carrinho -->
                <li id="cart-icon-container">
                    <a href="#" id="cart-icon" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <i class="bi bi-bag-fill"></i>
                    </a>
                    <span id="cart-count">0</span>
                </li>
                <li id="user-icon" class="user-icon-container">
                    @if (Auth::check())
                        <a href="#" id="user-dropdown-toggle"><i class="bi bi-person-fill"></i></a>
                        <div class="user-menu d-none">
                            <ul>
                                <li><a href="">Minha Conta</a></li>
                                <li><a href="">Meus Pedidos</a></li>
                                <li><a href="">Lista de Desejos <i class="fa-solid fa-heart"></i></a></li>
                                <li><a href="" id="logout-btn">Sair <i
                                            class="fa-solid fa-right-from-bracket"></i></a></li>
                            </ul>
                        </div>
                    @else
                        <a href="#" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
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
                                <!-- Input de quantidade -->
                                <form class="update-quantity-form"
                                    data-produto-id="{{ $produto->pivot->produto_id }}">
                                    @csrf
                                    <input type="number" name="quantidade"
                                        class="form-control form-control-sm text-center mx-2 update-quantity"
                                        value="{{ $produto->pivot->quantidade }}" min="1"
                                        style="width: 60px;">
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
                    <a href="{{ route('carrinho.finalizar') }}" class="btn btn-dark w-100">Finalizar Compra</a>
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

            <div class="alis">
                <img src="{{ asset('images/banner/alis - frase vectorizada_Prancheta 1 cópia (1).png') }}" alt="Banner">
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

        <div class="alis">
            <img src="{{ asset('images/banner/alis - frase vectorizada_Prancheta 1 cópia (1).png') }}" alt="Banner">
        </div>

        <div id="copyright">
            <p>{{ __('CNPJ "XXXXXXXX" - © 2024 Alis - Todos os direitos reservados.') }}</p>
            <p>{{ __('Desenvolvido por Tarcísio Kayck') }}</p>
        </div>
        <div class="alis">
            <img src="{{ asset('images/banner/alis - frase vectorizada_Prancheta 1 cópia (1).png') }}" alt="Banner">
        </div>
    </footer>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulário de Login -->
                    <div class="modal-body">
                        <!-- Formulário de Login -->
                        <form id="loginForm" action="{{route('cliente.store')}}" method="POST">
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
                        <form id="registerForm" action="{{route('cliente.store')}}" method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="id_tipos_usuarios" value="3">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="emailCadastro" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="emailCadastro" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="senhaCadastro" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senhaCadastro" name="senha" required>
                            </div>
                            <div class="mb-3">
                                <label for="senhaConfirm" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control" id="senhaConfirm" name="senha_confirmation" required>
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
{{-- {!! Toastr::message() !!} --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

<script>
    document.getElementById('showRegister').addEventListener('click', function() {
        document.getElementById('loginForm').style.display = 'none';
        document.getElementById('registerForm').style.display = 'block';
    });
    document.getElementById('showLogin').addEventListener('click', function() {
        document.getElementById('registerForm').style.display = 'none';
        document.getElementById('loginForm').style.display = 'block';
    });

    document.addEventListener('DOMContentLoaded', function() {
        const userDropdownToggle = document.getElementById('user-dropdown-toggle');
        const userMenu = document.querySelector('.user-menu');
        const openLoginModal = document.getElementById('open-login-modal');
        const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));

        // Se o usuário estiver logado, exibe o dropdown
        if (userDropdownToggle) {
            userDropdownToggle.addEventListener('click', function(event) {
                event.preventDefault();
                userMenu.classList.toggle('d-none');
            });

            // Fecha o dropdown ao clicar fora dele
            document.addEventListener('click', function(event) {
                if (!userDropdownToggle.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('d-none');
                }
            });
        }

        // Se o usuário não estiver logado, abre a modal de login
        if (openLoginModal) {
            openLoginModal.addEventListener('click', function(event) {
                event.preventDefault();
                loginModal.show();
            });
        }
    });
    //nav
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

    document.getElementById('nav-toggle').addEventListener('click', function() {
        const navbar = document.getElementById('navbar');
        navbar.classList.toggle('active');
    });

    //cupom

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('btnCupom').addEventListener('click', function() {
            this.classList.add('d-none');
            document.getElementById('inputCupom').classList.remove('d-none');
            document.getElementById('applyCupom').classList.remove('d-none');
        });

        document.getElementById('applyCupom').addEventListener('click', function() {
            const codigo = document.getElementById('inputCupom').value;
            const pedidoInput = document.getElementById('pedidoId');
            if (pedidoInput) {
                const pedidoId = pedidoInput.value;
                console.log("Pedido ID:", pedidoId); // Verifique se o pedidoId está correto
            } else {
                console.warn("O input pedidoId não foi encontrado na página.");
            }

            fetch(`/pedido/${pedidoId}/aplicar-cupom`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        codigo: codigo
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Cupom aplicado com sucesso!');
                        document.querySelector('.text-end strong').textContent =
                            `Total: R$ ${data.novoTotal}`;
                    } else {
                        alert('Erro ao aplicar o cupom: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro ao aplicar o cupom:', error);
                    alert('Ocorreu um erro ao aplicar o cupom');
                });
        });
    });

    //carrinho

    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".update-quantity").forEach(input => {
            input.addEventListener("change", function() {
                let form = this.closest(".update-quantity-form");
                let produtoId = form.dataset.produtoId;
                let quantidade = this.value;

                // Envia os dados via AJAX
                fetch(`/carrinho/atualizar/${produtoId}`, {
                        method: 'POST',
                        body: new FormData(form),
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                                .value
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            let produtoElement = form.closest('li');
                            let subtotal = data.produtos.find(produto => produto.pivot
                                    .produto_id == produtoId).pivot.quantidade * data
                                .produtos.find(produto => produto.pivot.produto_id ==
                                    produtoId).preco;

                            // Atualiza o subtotal do produto
                            produtoElement.querySelector('.me-auto small:last-child')
                                .textContent =
                                `Total: R$ ${subtotal.toFixed(2).replace('.', ',')}`;

                            // Atualiza o total do carrinho
                            document.querySelector(".offcanvas-body h5 strong")
                                .textContent = `Total: R$ ${data.total.replace('.', ',')}`;
                        } else {
                            alert(data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao atualizar o carrinho:', error);
                    });
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".remover-produto").forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                const form = btn.closest("form");
                form.submit(); // Envia o formulário normalmente
            });
        });
    });
</script>

</html>
