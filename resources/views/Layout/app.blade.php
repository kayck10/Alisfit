<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
            background-color: #f8f9fa;
            /* Fundo claro */
            color: #000;
            /* Texto preto */
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            /* Fundo branco dos cards */
        }

        .offcanvas {
            width: 250px;
            background-color: #ffffff;
            /* Fundo branco da sidebar */
        }

        .nav-link.active {
            background-color: #000;
            /* Cor ativa no menu */
            color: #fff !important;
        }

        .dropdown-menu {
            background-color: #11171dd7;
            /* Cor de fundo do dropdown */
            border: none;
        }

        .dropdown-item {
            color: #fff;
        }

        .dropdown-item:hover {
            background-color: #131c25;
            /* Hover no item do dropdown */
            color: #fff;
        }

        .navbar {
            background-color: #ffffff;
            /* Cor de fundo da navbar */
        }

        .navbar .navbar-brand img {
            width: 150px;
            height: 110px;
        }

        .navbar-nav .nav-link {
            color: #000;
        }

        .navbar-nav .nav-link:hover {
            color: #0e161f;
        }

        .btn-primary {
            background-color: #0d141b;
            border: none;
        }

        .btn-primary:hover {
            background-color: #212224;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #000000;
            color: white;
            text-align: center;
            padding: 10px 0;
        }
    </style>
    @stack('styles')
</head>

<body>
    <!-- Conteúdo principal -->
    <div class="container-fluid">
        <div class="row">
            <!-- Navbar -->
            <nav class="navbar navbar-light bg-light shadow-sm">
                <div class="container-fluid">
                    <button class="btn btn-dark" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#menuHamburguer" aria-controls="menuHamburguer">
                        ☰
                    </button>
                    <span class="navbar-brand">
                        <img src="{{ asset('images/ALis - nova logo-03.png') }}" alt="Logo">
                    </span>

                    <form action="" method="POST" class="d-inline">
                        @csrf
                        <div class="d-flex align-items-center">

                            <!-- Ícone de perfil -->
                            <a class="p-0 " style="font-size: 1.4rem;" href="">
                                <i class="bi bi-person-circle text-dark"></i>
                            </a>

                            <!-- Ícone de logout -->
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn">
                                    <i style="font-size: 1.4rem;" class="bi bi-box-arrow-right text-dark"></i>
                                </button>
                            </form>


                        </div>
                    </form>
                </div>
            </nav>

            <!-- Offcanvas -->
            <div class="offcanvas offcanvas-start bg-light text-dark" tabindex="-1" id="menuHamburguer"
                aria-labelledby="menuHamburguerLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="menuHamburguerLabel">
                        <img style="width: 150px;" src="{{ asset('images/ALis - nova logo-03.png') }}" alt="Logo">
                    </h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="offcanvas"
                        aria-label="Fechar"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active py-3" href="{{ route('dashboard') }}">Visão Geral</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link py-3 text-dark" href="{{ route('pedidos.index') }}">Pedidos</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link py-3 text-dark" href="{{route('financeiro.index')}}">Financeiro</a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark py-3" href="#" id="dropdownMenuGraficos"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"> Coleções
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuGraficos">
                                <li>
                                    <a class="dropdown-item" href="{{ route('colecoes.create') }}">Criar</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('colecoes.index') }}">Ver Todos</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark py-3" href="#" id="dropdownMenuGraficos"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"> Produtos
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuGraficos">
                                <li>
                                    <a class="dropdown-item" href="{{ route('produtos.create') }}">Criar</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('produtos.index') }}">Ver Todos</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark py-3" href="#" id="dropdownMenuGraficos"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"> Cupons
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuGraficos">
                                <li>
                                    <a class="dropdown-item" href="{{ route('cupons.create') }}">Criar</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('cupons.index')}}">Ver Todos</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle text-dark py-3" href="#" id="dropdownMenuGraficos"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false"> Usuários
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuGraficos">
                                <li>
                                    <a class="dropdown-item" href="{{ route('users.create') }}">Criar</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('users.index')}}">Ver Todos</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="container mt-4">
            @yield('content')
        </div>
    </div>

    <footer class="mt-5">
        <p>&copy; {{ date('Y') }} - Todos os direitos reservados.</p>
        <p><small>Desenvolvido por Tarcisio Kayck</small></p>
    </footer>

    <script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
    <script src="http://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
    {{-- {!! Toastr::message() !!} --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    @stack('scripts')
</body>

</html>
