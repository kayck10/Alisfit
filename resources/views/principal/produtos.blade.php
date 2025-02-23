@extends('Layout.principal')

@section('content')
<style>
    .container {
        margin-top: 150px;
    }

    /* Ajuste na exibição do produto */
    .product-card {
        position: relative;
        overflow: hidden;
        border-radius: 0;
        box-shadow: none;
        background: transparent;
        padding: 0;
        text-align: center;
    }

    .product-card img {
        width: 100%;
        height: 500px;
        object-fit: cover;
    }

    /* Sobreposição ao passar o mouse */
    .hover-overlay {    
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        background: rgba(255, 255, 255, 0.9);
        padding: 15px;
        opacity: 0;
        transition: opacity 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .product-card:hover .hover-overlay {
        opacity: 1;
    }

    /* Nome e preço do produto */
    .product-name, .product-price {
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px;
    }

    /* Botão "Adicionar à Sacola" */
    .btn-primary {
        background: blue;
        border: none;
        padding: 12px 18px;
        font-size: 16px;
        margin-top: 10px;
    }

    /* Tamanhos */
    .sizes {
        display: flex;
        gap: 8px;
        margin-bottom: 10px;
    }

    .size-option {
        background: #fff;
        border: 1px solid #000;
        padding: 8px 12px;
        font-size: 14px;
        cursor: pointer;
        color: #000;
        font-weight: bold;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
    }

    /* Posicionamento dos filtros */
    .filters {
        position: fixed;
        left: 40px;
        top: 150px;
        width: 250px;
        z-index: 1000;
    }

    /* Responsividade */
    @media (max-width: 992px) {
        .col-md-4 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        .filters {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
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
        <!-- Filtros -->
        <aside class="filters">
            <h4>FILTROS</h4>
            <div class="accordion" id="filtersAccordion">
                @php
                    $filtros = ['Categoria', 'Marcas', 'Modalidade', 'Tamanho', 'Cores e Estampas', 'Tecnologias Específicas', 'Gênero'];
                @endphp
                @foreach ($filtros as $index => $filtro)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $index }}">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
                                {{ $filtro }}
                            </button>
                        </h2>
                        <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#filtersAccordion">
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

        <!-- Listagem de Produtos -->
        <main class="col-md-9 offset-md-3">
            <div class="row">
                @foreach($produtos as $produto)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <img src="{{ asset('storage/' . $produto->imagem) }}" class="img-fluid" alt="{{ $produto->nome }}">

                        <!-- Sobreposição ao passar o mouse -->
                        <div class="hover-overlay">
                            <div class="sizes">
                                @foreach($produto->tamanhos as $tamanho)
                                    <span class="size-option">{{ $tamanho->desc }}</span>
                                @endforeach
                            </div>
                            <button class="btn btn-primary">ADICIONAR À SACOLA</button>
                        </div>

                        <p class="product-name">{{ $produto->nome }}</p>
                        <p class="product-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </main>
    </div>
</div>

<!-- Bootstrap JS para funcionamento dos dropdowns -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection
