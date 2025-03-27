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
                    <h2 class="mb-5"><strong>DROP SATUS</strong></h2>

                    <h4>Filtrar</h4>
                    <div class="accordion" id="filtersAccordion">
                        @php
                            $filtros = [
                                'Categoria' => $colecoes, // Coleções
                                'Tamanho' => $tamanhos, // Tamanhos
                                'Gênero' => $generos, // Gêneros
                                'Cor' => $cores, // Cores (substitua $cores pelos dados reais)
                            ];
                        @endphp
                        @foreach ($filtros as $filtroNome => $opcoes)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $loop->index }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $loop->index }}" aria-expanded="false"
                                        aria-controls="collapse{{ $loop->index }}">
                                        {{ $filtroNome }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading{{ $loop->index }}" data-bs-parent="#filtersAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group">
                                            @foreach ($opcoes as $opcao)
                                                <li class="list-group-item">
                                                    <input type="checkbox" name="{{ strtolower($filtroNome) }}[]"
                                                        value="{{ $opcao->id ?? $opcao }}"
                                                        id="{{ strtolower($filtroNome) }}_{{ $opcao->id ?? $opcao }}">
                                                    <label for="{{ strtolower($filtroNome) }}_{{ $opcao->id ?? $opcao }}">
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
                </div>
            </aside>
        </div>

        <!-- Produtos -->
        <main class="col-md-9 offset-md-3">
            <div class="row">
                @foreach ($produtos as $produto)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="product-card">
                            <a href="{{ route('produto.detalhes', ['id' => $produto->id]) }}">
                                @if ($produto->imagens->isNotEmpty())
                                    <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}"
                                        alt="{{ $produto->nome }}">
                                @else
                                    <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão">
                                @endif
                            </a>
                            <p class="product-name">{{ $produto->nome }}</p>
                            <p class="product-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>

                         <a href="{{ route('produto.detalhes', $produto->id) }}" class="btn btn-primary ">Ver Detalhes</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </main>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const aplicarFiltrosBtn = document.getElementById('aplicarFiltros');

            if (aplicarFiltrosBtn) {
                aplicarFiltrosBtn.addEventListener('click', function() {
                    const filtros = {
                        colecao_id: [],
                        tamanho_id: [],
                        genero_id: [],
                        cor: [],
                    };

                    document.querySelectorAll('input[name="categoria[]"]:checked').forEach((checkbox) => {
                        filtros.colecao_id.push(checkbox.value);
                    });

                    document.querySelectorAll('input[name="tamanho[]"]:checked').forEach((checkbox) => {
                        filtros.tamanho_id.push(checkbox.value);
                    });

                    document.querySelectorAll('input[name="genero[]"]:checked').forEach((checkbox) => {
                        filtros.genero_id.push(checkbox.value);
                    });

                    document.querySelectorAll('input[name="cor[]"]:checked').forEach((checkbox) => {
                        filtros.cor.push(checkbox.value);
                    });

                    fetch('{{ route('drops.filtrar') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify(filtros),
                        })
                        .then(response => response.json())
                        .then(data => {
                            const produtosContainer = document.querySelector(
                                '.row');
                            produtosContainer.innerHTML = ''; // Limpar o conteúdo atual

                            data.produtos.forEach(produto => {
                                const produtoHTML = `
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="product-card">
                             <a href="/loja/produto/${produto.id}">
                                    ${produto.imagens.length > 0 ?
                                        `<img src="{{ asset('storage/') }}/${produto.imagens[0].imagem}" alt="${produto.nome}">` :
                                        `<img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão">`
                                    }
                                </a>
                                <p class="product-name">${produto.nome}</p>
                                <p class="product-price">R$ ${parseFloat(produto.preco).toFixed(2).replace('.', ',')}</p>
                               <form action="{{ route('carrinho.adicionar', ['produtoId' => $produto->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary mt-2">Adicionar à sacola</button>
                            </form>

                            </div>
                        </div>
                    `;
                                produtosContainer.insertAdjacentHTML('beforeend', produtoHTML);
                            });
                        })
                        .catch(error => {
                            console.error('Erro ao filtrar produtos:', error);
                        });
                });
            } else {
                console.error('Botão "aplicarFiltros" não encontrado.');
            }
        });
    </script>
@endsection
