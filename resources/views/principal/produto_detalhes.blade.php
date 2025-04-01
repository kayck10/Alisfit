@extends('Layout.principal')

<style>
    .produto-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 40px;
        margin-top: 80px;
    }

    .produto-container .col-md-5 {
        flex: 1;
    }

    .produto-imagem {
        flex: 1;
        max-width: 500px;
    }

    .produto-container .col-md-7 {
        flex: 2;
    }

    /* Container da imagem principal */
    .img-produto-container {
        width: 100%;
        height: 500px;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        overflow: hidden;
        border-radius: 10px;
        background-color: #f8f9fa;
    }

    .img-produto {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
        transition: transform 0.3s ease;
        border-radius: 10px;
    }

    .miniaturas-container {
        display: flex;
        gap: 10px;
        margin-top: 20px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .miniatura {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 5px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .miniatura:hover {
        border-color: #007bff;
        transform: scale(1.1);
        z-index: 10;
    }

    .miniatura.active {
        border-color: #007bff;
        opacity: 1;
    }

    /* Estilos para o botão */
    .btn-dark {
        white-space: nowrap;
        padding: 12px 24px;
        font-size: 16px;
        min-width: 200px;
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

    @media (max-width: 768px) {
        .produto-container {
            flex-direction: column;
            text-align: center;
            align-items: center;
            margin-top: 40px;
        }

        .img-produto-container {
            height: 350px;
        }

        .produto-info {
            flex: 2;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .col-md-5 {
            order: 1;
        }

        .img-produto {
            max-width: 90%;
            height: auto;
        }

        .miniatura {
            width: 60px;
            height: 60px;
        }

        .btn-dark {
            width: 100%;
            margin-top: 20px;
        }

        .sizes-container,
        .color-box {
            justify-content: center;
        }
    }

    /* Estilos para tamanhos e cores */
    .color-box {
        width: 30px;
        height: 30px;
        display: inline-block;
        border: 1px solid #ddd;
        margin-right: 5px;
        cursor: pointer;
        border-radius: 50%;
        transition: border-color 0.3s, transform 0.3s;
    }

    .color-box:hover {
        border-color: #000;
        transform: scale(1.1);
    }

    .color-box.selected {
        border: 2px solid #998989;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    .size-box {
        display: inline-block;
        padding: 10px 20px;
        margin-right: 10px;
        border: 1px solid #ddd;
        cursor: pointer;
        background-color: #f8f8f8;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .size-box:hover {
        background-color: #e0e0e0;
        transform: scale(1.05);
    }

    .size-box.selected {
        background-color: #000;
        color: #fff;
        border-color: #333;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    }

    /* Animações */
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

    /* Mensagem de aviso */
    .selecao-aviso {
        color: #d9534f;
        font-weight: bold;
        margin-top: 10px;
        text-align: center;
        font-size: 16px;
        display: none;
    }

    .selecao-aviso.active {
        display: block;
    }

    /* Produtos relacionados */
    .upsell-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
    }

    .upsell-item {
        width: 250px;
        text-align: center;
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease-in-out forwards;
    }

    .upsell-item img {
        width: 100%;
        height: 300px;
        object-fit: cover;
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .upsell-item img:hover {
        transform: scale(1.05);
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
            <div class="row">
                <div class="col-12">
                    <!-- Imagem principal -->
                    <div class="img-produto-container mb-3">
                        @if ($produto->imagens->isNotEmpty())
                            <img id="imagem-principal" src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}"
                                alt="{{ $produto->nome }}" class="img-produto">
                        @else
                            <img id="imagem-principal" src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão"
                                class="img-produto">
                        @endif
                    </div>

                    <!-- Miniaturas -->
                    <div class="miniaturas-container">
                        @foreach ($produto->imagens as $imagem)
                            @php
                                $tempProduto = new stdClass();
                                $tempProduto->imagens = collect([$imagem]);
                            @endphp
                            <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($tempProduto) }}"
                                alt="{{ $produto->nome }}" class="miniatura @if ($loop->first) active @endif"
                                onclick="trocarImagemPrincipal('{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($tempProduto) }}')">
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Restante do conteúdo -->
            <div class="row mt-4">
                <div class="col-12 produto-info">
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
                    <div class="selecao-aviso" id="aviso-selecao">Por favor, escolha tanto o tamanho quanto a cor para
                        adicionar ao carrinho.</div>
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form action="{{ route('carrinho.adicionar', $produto->id) }}" method="POST"
                        onsubmit="return validarSelecao()">
                        @csrf
                        <input type="hidden" name="tamanho_id" id="tamanho_id">
                        <input type="hidden" name="cor" id="cor">

                        <div class="d-grid gap-2 col-12 col-md-6 mx-auto">
                            <input type="hidden" name="quantidade" value="1">
                            <button type="submit" class="btn btn-dark btn-lg mt-2">Adicionar à sacola</button>
                        </div>
                    </form>
                    <h6 class="mt-5 mb-5">DESCRIÇÃO DO PRODUTO</h6>
                    <p>{{ $produto->descricao }}</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="mt-5">

    <div class="upsell-container mb-4 text-center">
        <h5 class="mb-5"><strong> VOCÊ TAMBÉM VAI GOSTAR </strong></h5>
        <div class="upsell-list">
            @foreach ($produtosRelacionados as $relacionado)
                <div class="upsell-item text-center mx-3 mb-4">
                    <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($relacionado) }}"
                        alt="{{ $relacionado->nome }}" class="img-fluid">
                    <h5 class="mt-3">{{ $relacionado->nome }}</h5>
                    <p>R$ {{ number_format($relacionado->preco, 2, ',', '.') }}</p>
                    <a href="{{ route('produto.detalhes', $relacionado->id) }}" class="btn btn-dark">Ver Detalhes</a>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function trocarImagemPrincipal(novaImagem) {
            document.getElementById('imagem-principal').src = novaImagem;
            document.querySelectorAll('.miniatura').forEach(img => {
                img.classList.remove('active');
            });

            event.target.classList.add('active');
        }

        document.addEventListener("DOMContentLoaded", function() {
            let tamanhoSelecionado = null;
            let corSelecionada = null;

            // Seleção de tamanhos
            document.querySelectorAll('.size-box').forEach(box => {
                box.addEventListener('click', function() {
                    document.querySelectorAll('.size-box').forEach(b => b.classList.remove(
                        'selected'));
                    this.classList.add('selected');
                    tamanhoSelecionado = this.getAttribute('data-tamanho-id');
                    document.getElementById('tamanho_id').value = tamanhoSelecionado;
                    document.getElementById('aviso-selecao').classList.remove('active');
                });
            });

            // Seleção de cores
            document.querySelectorAll('.color-box').forEach(box => {
                box.addEventListener('click', function() {
                    document.querySelectorAll('.color-box').forEach(b => b.classList.remove(
                        'selected'));
                    this.classList.add('selected');
                    corSelecionada = this.style.backgroundColor;
                    document.getElementById('cor').value = corSelecionada;
                    document.getElementById('aviso-selecao').classList.remove('active');
                });
            });

            // Validação antes de enviar o formulário
            function validarSelecao() {
                if (!tamanhoSelecionado || !corSelecionada) {
                    document.getElementById('aviso-selecao').classList.add('active');
                    return false;
                }
                return true;
            }

            // Efeito hover nas miniaturas
            document.querySelectorAll('.miniatura').forEach(img => {
                img.addEventListener('mouseenter', function() {
                    this.style.transform = 'scale(1.1)';
                    this.style.zIndex = '10';
                });

                img.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        this.style.transform = 'scale(1)';
                        this.style.zIndex = '1';
                    }
                });
            });
        });
    </script>
@endsection
