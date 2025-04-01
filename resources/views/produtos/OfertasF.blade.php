@extends('Layout.principal')

@section('content')
<main class="col-md-9 offset-md-3">
    <div class="container">
        <h3 class="mb-4">Ofertas de Conjuntos Femininos</h3>

        @if ($produtos->isEmpty())
            <div class="alert alert-warning text-center">
                Nenhuma camiseta masculina disponível no momento.
            </div>
        @else
            <div class="row">
                @foreach ($produtos as $produto)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6 mb-4">
                        <div class="product-card">
                            <a href="{{ route('produto.detalhes', ['id' => $produto->id]) }}">
                                @if ($produto->imagens->isNotEmpty())
                                <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}"
                                    alt="{{ $produto->nome }}" class="img-custom">
                            @else
                                <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão">
                            @endif
                            </a>
                            <p class="product-name">{{ $produto->nome }}</p>
                            <p class="product-price">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>


                                <a href="{{ route('produto.detalhes', $produto->id) }}" class="btn btn-primary">Ver Detalhes</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</main>

<style>
    .container {
        margin-top: 100px;
    }

    .btn-primary {
        background: black !important;
        border: none;
        padding: 8px 14px;
        font-size: 14px;
        color: white;
        width: 90%;
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
        margin: auto;
    }

    .product-card img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        border-radius: 6px;
        transition: transform 0.3s ease;
    }

    @media (max-width: 768px) {
        .product-card img {
            height: auto;
            aspect-ratio: 3/4;
        }
    }

    .product-card img:hover {
        transform: scale(1.05);
    }

    .product-card:hover {
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        transform: translateY(-5px);
    }

    .product-name, .product-price, .btn-primary {
        margin-top: 6px;
    }

    @media (max-width: 576px) {
        .col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }
    }
</style>
@endsection
