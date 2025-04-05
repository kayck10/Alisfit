@extends('Layout.principal')

@section('content')
    <style>
        .container {
            margin-top: 150px;
        }

        .btn-primary {
            background: black !important;
            border: none;
            padding: 6px 12px;
            font-size: 13px;
            margin-top: 5px;
            color: white;
            width: 75%;
            transition: background 0.3s ease, transform 0.2s ease;
            border-radius: 4px;
        }

        .btn-primary:hover {
            background: #333 !important;
            transform: scale(1.05);
        }

        .filters {
            position: fixed;
            left: 40px;
            top: 150px;
            width: 250px;
            z-index: 1000;
            background: transparent;
            border: none;
        }

        .accordion-item {
            border: none !important;
        }

        .list-group-item {
            border: none !important;
            background: transparent;
        }

        .col-md-3 {
            padding: 8px;
        }

        .product-card {
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            width: 100%;
            max-width: 300px;
            min-width: 260px;
            margin: auto;
        }

        .product-card img {
            width: 100%;
            height: 320px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card img:hover {
            transform: scale(1.05);
        }

        .product-card:hover {
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        .product-name,
        .product-price,
        .btn-primary {
            margin-top: 6px;
        }

        .btn-primary {
            width: 90%;
            padding: 10px 14px;
            font-size: 15px;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            display: none;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 992px) {
            .col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .filters {
                position: static;
                width: 100%;
                margin-bottom: 20px;
            }

            .filters-toggle {
                display: block;
                margin-bottom: 10px;
            }

            .filters-collapse {
                display: none;
            }

            .filters-collapse.show {
                display: block;
            }
        }

        @media (min-width: 1200px) {
            .col-md-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }

        @media (max-width: 576px) {
            .col-md-4 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }
    </style>

    <div class="loading-overlay">
        <div class="spinner"></div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Botão de Toggle para o Filtro (Visível apenas em telas menores) -->
            <button class="btn btn-primary filters-toggle d-md-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                Filtrar
            </button>

            <!-- Filtros -->
            <aside class="filters">
                <div class="collapse d-md-block" id="filtersCollapse">
                    <h2 class="mb-5"><strong>DROP @foreach ($colecoes as $colecao)
                                {{ $colecao->nome }}</a>
                            @endforeach
                        </strong></h2>

                    <h4>Filtrar</h4>
                    <div class="accordion" id="filtersAccordion">
                        @php
                            $filtros = [
                                'categoria' => $colecoes, // Coleções
                                'tamanho' => $tamanhos, // Tamanhos
                                'gênero' => $generos, // Gêneros
                                'cor' => $cores, // Cores
                            ];
                        @endphp
                        @foreach ($filtros as $filtroNome => $opcoes)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false"
                                        aria-controls="collapse{{ $loop->index }}">
                                        {{ ucfirst($filtroNome) }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#filtersAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @foreach ($opcoes as $opcao)
                                                <li class="list-group-item">
                                                    <input type="checkbox" name="{{ $filtroNome }}[]"
                                                        value="{{ $opcao->id ?? $opcao }}"
                                                        id="{{ $filtroNome }}_{{ $opcao->id ?? $opcao }}">
                                                    <label for="{{ $filtroNome }}_{{ $opcao->id ?? $opcao }}">
                                                        {{ $opcao->nome ?? ($opcao->desc ?? $opcao) }}
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Botão para aplicar os filtros -->
                    <button type="button" id="aplicarFiltros" class="btn btn-primary mt-3">Aplicar Filtros</button>
                    <button type="button" id="limparFiltros" class="btn btn-secondary mt-2">Limpar Filtros</button>
                </div>
            </aside>
        </div>

        <!-- Produtos -->
        <main class="col-md-9 offset-md-3">
            <div class="row" id="produtosContainer">
                @foreach ($produtos as $produto)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="product-card">
                            <a href="{{ route('produto.detalhes', ['id' => $produto->id]) }}">
                                @if ($produto->imagens->isNotEmpty())
                                    <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}"
                                        alt="{{ $produto->nome }}" class="img-fluid">
                                @else
                                    <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão" class="img-fluid">
                                @endif
                            </a>
                            <p class="product-name">{{ $produto->nome }}</p>
                            <p class="product-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                            <a href="{{ route('produto.detalhes', $produto->id) }}" class="btn btn-primary">Ver Detalhes</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aplicarFiltrosBtn = document.getElementById('aplicarFiltros');
            const limparFiltrosBtn = document.getElementById('limparFiltros');
            const loadingOverlay = document.querySelector('.loading-overlay');

            function showLoading() {
                loadingOverlay.style.display = 'flex';
            }

            function hideLoading() {
                loadingOverlay.style.display = 'none';
            }

            if (aplicarFiltrosBtn) {
                aplicarFiltrosBtn.addEventListener('click', function() {
                    showLoading();

                    // Coletar todos os filtros selecionados
                    const filtros = {
                        colecao_id: Array.from(document.querySelectorAll('input[name="categoria[]"]:checked')).map(el => el.value),
                        tamanho_id: Array.from(document.querySelectorAll('input[name="tamanho[]"]:checked')).map(el => el.value),
                        genero_id: Array.from(document.querySelectorAll('input[name="gênero[]"]:checked')).map(el => el.value),
                        cor: Array.from(document.querySelectorAll('input[name="cor[]"]:checked')).map(el => el.value)
                    };

                    // Fazer a requisição AJAX
                    fetch('{{ route("drops.filtrar") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(filtros)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na requisição');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const produtosContainer = document.getElementById('produtosContainer');
                        produtosContainer.innerHTML = '';

                        if (data.produtos.length === 0) {
                            produtosContainer.innerHTML = `
                                <div class="col-12 text-center py-5">
                                    <h4>Nenhum produto encontrado com os filtros selecionados</h4>
                                </div>
                            `;
                            return;
                        }

                        data.produtos.forEach(produto => {
                            const imagem = produto.imagens.length > 0
                                ? produto.imagens[0].url
                                : "{{ asset('images/banner/12.png') }}";

                            const produtoHTML = `
                                <div class="col-md-3 col-sm-6 mb-4">
                                    <div class="product-card">
                                        <a href="/loja/produto/${produto.id}">
                                            <img src="${imagem}" alt="${produto.nome}" class="img-fluid">
                                        </a>
                                        <p class="product-name">${produto.nome}</p>
                                        <p class="product-price">R$ ${produto.preco.toFixed(2).replace('.', ',')}</p>
                                        <a href="/loja/produto/${produto.id}" class="btn btn-primary">Ver Detalhes</a>
                                    </div>
                                </div>
                            `;
                            produtosContainer.insertAdjacentHTML('beforeend', produtoHTML);
                        });
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Ocorreu um erro ao filtrar os produtos. Por favor, tente novamente.');
                    })
                    .finally(() => {
                        hideLoading();
                    });
                });
            }

            if (limparFiltrosBtn) {
                limparFiltrosBtn.addEventListener('click', function() {
                    // Desmarca todos os checkboxes
                    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                        checkbox.checked = false;
                    });

                    // Recarrega a página para mostrar todos os produtos
                    window.location.reload();
                });
            }
        });
    </script>
@endsection
