@extends('Layout.principal')

<style>
      .produto-container {
        display: flex;
        gap: 40px;
        margin-top: 80px;
        flex-wrap: wrap;
    }

    .produto-container .col-md-5 {
        flex: 1;
    }

    .produto-container .col-md-7 {
        flex: 2;
    }

    .img-produto {
        max-width: 100%;
        max-height: 600px;
        object-fit: cover;
        border-radius: 10px;
    }


    @media (max-width: 768px) {
        .produto-container {
            flex-direction: column;
            text-align: center;
            align-items: center;
        }

        .produto-info {
            width: 100%;
        }

        .img-produto {
            max-width: 90%;
            height: auto;
        }

        .upsell-list {
            flex-direction: column;
            align-items: center;
        }

        .upsell-item {
            width: 100%;
            max-width: 300px;
        }
    }

    .upsell-item img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 10px;
    }

    .upsell-item h5,
    .upsell-item p {
        margin-top: 10px;
        /* Dando um espaço entre a imagem e o conteúdo */
    }

    .upsell-item a {
        margin-top: 15px;
        /* Dando um espaço entre o preço e o botão */
    }

    .color-box {
        width: 30px;
        height: 30px;
        display: inline-block;
        border: 1px solid #ddd;
        margin-right: 5px;
        cursor: pointer;
    }

    .size-box {
        display: inline-block;
        padding: 10px 20px;
        margin-right: 10px;
        border: 1px solid #ddd;
        cursor: pointer;
        background-color: #f8f8f8;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .size-box:hover {
        background-color: #e0e0e0;
    }

    .size-box.selected {
        background-color: #000;
    }

    /* Animação de entrada na página */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .container {
        animation: fadeInUp 0.8s ease-in-out;
    }

    /* Efeito de zoom ao passar o mouse na imagem principal */
    .img-produto {
        overflow: hidden;
        display: block;
        transition: transform 0.3s ease-in-out;
    }


    .img-produto:hover {
        transform: scale(1.1);
        /* Aumenta o tamanho da imagem dentro do espaço */
    }

    .upsell-item img {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }

    .upsell-item img:hover {
        transform: scale(1.05);
        box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* Animação do botão */
    .btn-dark {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease-in-out;
    }

    .btn-dark:hover {
        transform: scale(1.05);
        background-color: #333;
    }

    .btn-dark::before {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        width: 300%;
        height: 300%;
        background: rgba(255, 255, 255, 0.2);
        transition: all 0.5s ease-in-out;
        transform: translate(-50%, -50%) scale(0);
        border-radius: 50%;
    }

    .btn-dark:hover::before {
        transform: translate(-50%, -50%) scale(1);
    }

    /* Suavização na seleção de tamanhos */
    .size-box {
        transition: all 0.3s ease-in-out;
    }

    .size-box:hover {
        transform: scale(1.1);
    }

    /* Efeito na entrada dos produtos relacionados */
    .upsell-item {
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease-in-out forwards;
    }

    .upsell-item:nth-child(1) {
        animation-delay: 0.1s;
    }

    .upsell-item:nth-child(2) {
        animation-delay: 0.2s;
    }

    .upsell-item:nth-child(3) {
        animation-delay: 0.3s;
    }

    .upsell-item:nth-child(4) {
        animation-delay: 0.4s;
    }
</style>

@section('content')
    <div class="container">
        <div class="produto-container">
            <div class="col-md-5">
                @if ($produto->imagens->isNotEmpty())
                    <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}" alt="{{ $produto->nome }}"
                        class="img-fluid img-produto">
                @else
                    <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão" class="img-fluid img-produto">
                @endif
            </div>
            <div class="col-md-7 produto-info">
                <h2>{{ $produto->nome }}</h2>
                <h4>R$ {{ number_format($produto->preco, 2, ',', '.') }}</h4>

                <label class="mt-3">Tamanhos disponíveis:</label>
                <div class="sizes-container mt-4 mb-4">
                    @foreach ($produto->tamanhos as $tamanho)
                        <div class="size-box" data-tamanho-id="{{ $tamanho->pivot->tamanho_id }}">
                            {{ $tamanho->desc }}
                        </div>
                    @endforeach
                </div>

                <label>Cores disponíveis:</label>
                <div class="mt-3">
                    @foreach ($produto->tamanhos as $tamanho)
                        @php
                            $corHex = $cor_map[$tamanho->pivot->cor] ?? '#000000';
                        @endphp
                        <span class="color-box" style="background-color: {{ $corHex }}"></span>
                    @endforeach
                </div>

                <hr>

                <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST">
                    @csrf
                    <div class="d-grid gap-2 col-6">
                        <input type="hidden" name="quantidade" value="1">
                        <div class="d-grid gap-2 col-6 text-center">
                            <button type="submit" class="btn btn-dark btn-lg mt-2">Adicionar à sacola</button>
                        </div>
                    </div>
                </form>
                <h6 class="mt-5 mb-5">DESCRIÇÃO DO PRODUTO</h6>
                <p>{{ $produto->descricao }}</p>
            </div>
        </div>
        <hr class="mt-5">
        <div class="upsell-container">
            <h5 class="mb-5"><strong> VOCÊ TAMBÉM VAI GOSTAR </strong></h5>
            <div class="upsell-list">
                @foreach ($produtosRelacionados as $relacionado)
                    <div class="upsell-item">
                        <img src="{{ asset('storage/' . ($relacionado->imagens->first()->imagem ?? 'images/banner/12.png')) }}"
                            alt="{{ $relacionado->nome }}">
                        <h5>{{ $relacionado->nome }}</h5>
                        <p>R$ {{ number_format($relacionado->preco, 2, ',', '.') }}</p>
                        <a href="{{ route('produto.detalhes', $relacionado->id) }}" class="btn btn-primary btn-sm">Ver
                            Produto</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.size-box').forEach(function(sizeBox) {
            sizeBox.addEventListener('click', function() {
                document.querySelectorAll('.size-box').forEach(function(box) {
                    box.classList.remove('selected');
                });
                sizeBox.classList.add('selected');
                let tamanhoId = sizeBox.getAttribute('data-tamanho-id');
                console.log('Tamanho selecionado:', tamanhoId);
            });
        });
    </script>
@endsection
