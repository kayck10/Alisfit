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
            /* Botão menor */
            font-size: 13px;
            margin-top: 5px;
            /* Menor espaço entre elementos */
            color: white;
            width: 75%;
            transition: background 0.3s ease, transform 0.2s ease;
            border-radius: 4px;
            /* Retangular */
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
            /* Reduz a distância entre os elementos */
        }

        .btn-primary {
            width: 90%;
            /* Ocupa melhor o espaço */
            padding: 10px 14px;
            font-size: 15px;
        }

        @media (max-width: 992px) {
            .col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }

            .filters {
                position: fixed;
                left: 20px;
                /* Aproxima mais da borda */
                top: 150px;
                width: 220px;
                /* Reduzi um pouco a largura */
                z-index: 1000;
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
            <aside class="filters">
                <h2 class="mb-5"><strong>DROP SATUS</strong></h2>

                <h4>Filtrar</h4>
                <div class="accordion" id="filtersAccordion">
                    @php
                        $filtros = ['Categoria', 'Marcas', 'Modalidade', 'Tamanho'];
                    @endphp
                    @foreach ($filtros as $index => $filtro)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="heading{{ $index }}">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse{{ $index }}" aria-expanded="false"
                                    aria-controls="collapse{{ $index }}">
                                    {{ $filtro }}
                                </button>
                            </h2>
                            <div id="collapse{{ $index }}" class="accordion-collapse collapse"
                                aria-labelledby="heading{{ $index }}" data-bs-parent="#filtersAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group">
                                        <li class="list-group-item">Opção 1</li>
                                        <li class="list-group-item">Opção 2</li>
                                        <li class="list-group-item">Opção 3</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </aside>

            <main class="col-md-9 offset-md-3">
                <div class="row">
                    @foreach ($produtos as $produto)
                        <div class="col-md-3 col-sm-6 mb-4">
                            <div class="product-card">
                            <a href="{{route('produto.detalhes')}}">   @if ($produto->imagens->isNotEmpty())
                                    <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}"
                                        alt="{{ $produto->nome }}">
                                @else
                                    <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão">
                                @endif
                            </a>
                                <p class="product-name">{{ $produto->nome }}</p>
                                <p class="product-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>

                                <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST">
                                    @csrf
                                    <input type="number" name="quantidade" value="1" min="1" class="form-control" style="width: 60px;">
                                    <button type="submit" class="btn btn-primary mt-2">Adicionar a sacola</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
