@extends('Layout.principal')

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
            position: fixed;
            left: 20px;
            top: 150px;
            width: 220px;
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

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center"> {{ $produtos->first()->colecao->nome ?? 'Coleção Desconhecida' }}</h2>

        <div class="row">
            @foreach ($produtos as $produto)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card card shadow-sm">
                      <a href="{{ route('produto.detalhes', $produto->id) }}">  <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}"
                            alt="{{ $produto->nome }}" class="card-img-top"></a>
                        <div class="card-body">
                            <h5 class="product-name card-title">{{ $produto->nome }}</h5>
                            <p class="product-description card-text">{{ $produto->descricao }}</p>
                            <p class="product-price fw-bold">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
