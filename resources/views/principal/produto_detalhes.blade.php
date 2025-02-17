@extends('Layout.principal')

<style>
    .produto-container {
        display: flex;
        gap: 40px;
        margin-top: 150px;
    }

    .img-produto {
        max-width: 100%;
        max-height: 600px;
        object-fit: cover;
        border-radius: 10px;
    }

    .produto-info {
        flex: 1;
    }

    .upsell-container {
        margin-top: 100px;
        margin-bottom: 100px;
        text-align: center;
    }

    .upsell-container h3 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .upsell-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .upsell-item {
        width: 300px;
        /* Definindo o tamanho fixo da imagem e do conteúdo */
        text-align: center;
        /* Centralizando o texto */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
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
                        <button type="submit" class="btn btn-dark btn-lg mt-2">Adicionar à sacola</button>
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
