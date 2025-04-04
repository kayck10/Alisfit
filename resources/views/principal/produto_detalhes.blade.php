@extends('Layout.principal')

<style>
    .produto-container {
        display: flex;
        flex-wrap: nowrap;
        justify-content: center;
        gap: 20px;
        margin-top: 80px;
    }

    .produto-imagem {
        flex: 1;
        max-width: 500px;
    }

    .img-produto-container {
        width: 400px;
        height: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        overflow: hidden;
    }

    .img-produto {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
        border-radius: 10px;
    }

    .miniaturas-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
        min-width: 80px;
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
    }

    .miniatura.active {
        border-color: #007bff;
    }

    .produto-info {
        flex: 2;
        padding: 0 20px;
    }

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

    /* Tabela de medidas */
    .tabela-container {
        margin-top: 40px;
    }

    .tabela-toggle {
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        color: #333;
        padding: 8px 15px;
        font-size: 14px;
        border-radius: 5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }

    .tabela-toggle:hover {
        background-color: #e9ecef;
    }

    .tabela-toggle i {
        margin-left: 8px;
        transition: transform 0.3s ease;
    }

    .tabela-toggle.active i {
        transform: rotate(180deg);
    }

    .tabela-medidas {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        display: none;
        /* Inicialmente escondida */
        animation: fadeIn 0.3s ease-in-out;
    }

    .tabela-medidas.show {
        display: table;
    }

    .tabela-medidas th,
    .tabela-medidas td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }

    .tabela-medidas th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    .tabela-medidas tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .tabela-medidas tr:hover {
        background-color: #f1f1f1;
    }

    .tabela-medidas .header-row {
        background-color: #333;
        color: white;
    }

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


    .upsell-container {
        width: 100%;
        overflow: hidden;
        padding: 0 15px;
    }

    .upsell-list {
        display: flex;
        gap: 15px;
        overflow-x: auto;
        padding-bottom: 20px;
        scrollbar-width: thin;
        scrollbar-color: #888 #f1f1f1;
    }

    .upsell-list::-webkit-scrollbar {
        height: 8px;
    }

    .upsell-list::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .upsell-list::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    .upsell-item {
        width: 250px;
        text-align: center;
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease-in-out forwards;
    }

    .upsell-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .upsell-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 5px;
    }

    .upsell-item h5 {
        font-size: 16px;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .upsell-item p {
        font-size: 14px;
        color: #333;
        margin-bottom: 10px;
    }

    @media (min-width: 768px) {
        .upsell-list {
            flex-wrap: wrap;
            justify-content: center;
            overflow-x: visible;
        }

        .upsell-item {
            width: 200px;
        }

        .upsell-image {
            height: 180px;
        }
    }

    @media (min-width: 992px) {
        .upsell-item {
            width: 220px;
        }
    }

    @media (max-width: 992px) {
        .produto-container {
            flex-wrap: wrap;
            gap: 30px;
        }

        .miniaturas-container {
            flex-direction: row;
            order: 2;
            width: 100%;
            justify-content: center;
        }

        .img-produto-container {
            order: 1;
            width: 100%;
            max-width: 400px;
        }

        .produto-info {
            order: 3;
            width: 100%;
            padding: 20px 0;
        }
    }

    @media (max-width: 768px) {
        .img-produto-container {
            height: 350px;
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

        .tabela-medidas {
            font-size: 14px;
        }
    }

    @media (max-width: 576px) {
        .produto-container {
            margin-top: 40px;
        }

        .img-produto-container {
            height: 300px;
        }

        .miniatura {
            width: 50px;
            height: 50px;
        }

        .tabela-medidas {
            font-size: 12px;
        }

        .tabela-medidas th,
        .tabela-medidas td {
            padding: 5px;
        }
    }
</style>

@section('content')
    <div class="container">
        <div class="produto-container">
            <!-- Miniaturas ao lado -->
            <div class="miniaturas-container">
                @foreach ($produto->imagens as $imagem)
                    @php
                        $tempProduto = new stdClass();
                        $tempProduto->imagens = collect([$imagem]);
                    @endphp
                    <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($tempProduto) }}" alt="{{ $produto->nome }}"
                        class="miniatura @if ($loop->first) active @endif"
                        onclick="trocarImagemPrincipal('{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($tempProduto) }}')">
                @endforeach
            </div>

            <!-- Imagem principal -->
            <div class="img-produto-container">
                @if ($produto->imagens->isNotEmpty())
                    <img id="imagem-principal" src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}"
                        alt="{{ $produto->nome }}" class="img-produto">
                @else
                    <img id="imagem-principal" src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão"
                        class="img-produto">
                @endif
            </div>

            <!-- Detalhes do Produto ao lado da Imagem -->
            <div class="produto-info">
                <h2>{{ $produto->nome }}</h2>
                <h4>R$ {{ number_format($produto->preco, 2, ',', '.') }}</h4>

                <label class="mt-3">Tamanhos disponíveis:</label>
                <div class="sizes-container mt-4 mb-4">
                    @foreach ($tamanhosAgrupados as $tamanho)
                        <div class="size-box" data-tamanho-id="{{ $tamanho['id'] }}"
                            data-cores='@json($tamanho['cores'])'>
                            {{ $tamanho['desc'] }}
                        </div>
                    @endforeach
                </div>

                <!-- Container das cores (inicialmente escondido) -->
                <div id="cores-container" style="display: none;">
                    <label>Cores disponíveis:</label>
                    <div class="mt-3" id="cores-opcoes"></div>
                </div>

                <hr>
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

                <!-- Tabela de Medidas recolhível -->
                <div class="tabela-container">
                    <button class="tabela-toggle" onclick="toggleTabelaMedidas()">
                        Ver Tabela de Medidas
                        <i class="fas fa-chevron-down"></i>
                    </button>

                    <table class="tabela-medidas">
                        <tr class="header-row">
                            <th>Tamanho</th>
                            <th>Comprimento (cm)</th>
                            <th>Largura (cm)</th>
                            <th>Altura (cm)</th>
                        </tr>
                        <tr>
                            <td>PP</td>
                            <td>65</td>
                            <td>45</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>P</td>
                            <td>70</td>
                            <td>50</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>M</td>
                            <td>75</td>
                            <td>55</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>G</td>
                            <td>80</td>
                            <td>60</td>
                            <td>10</td>
                        </tr>
                        <tr>
                            <td>GG</td>
                            <td>85</td>
                            <td>65</td>
                            <td>10</td>
                        </tr>
                    </table>
                    <p class="mt-3" style="display: none;" id="medidas-nota"><small>* Medidas aproximadas. Podem variar em
                            até 2cm.</small></p>
                </div>
            </div>
        </div>


        <hr class="mt-5">

        <div class="upsell-container mb-4">
            <h5 class="mb-4 text-center"><strong>VOCÊ TAMBÉM VAI GOSTAR</strong></h5>
            <div class="upsell-list">
                @foreach ($produtosRelacionados as $relacionado)
                    <div class="upsell-item">
                        <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($relacionado) }}"
                            alt="{{ $relacionado->nome }}" class="upsell-image">
                        <h5 class="mt-2">{{ $relacionado->nome }}</h5>
                        <p>R$ {{ number_format($relacionado->preco, 2, ',', '.') }}</p>
                        <a href="{{ route('produto.detalhes', $relacionado->id) }}" class="btn btn-dark btn-sm">Ver
                            Detalhes</a>
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let tamanhoSelecionado = null;
                let corSelecionada = null;

                // Seleção de tamanhos
                document.querySelectorAll('.size-box').forEach(box => {
                    box.addEventListener('click', function() {
                        // Remove seleção anterior
                        document.querySelectorAll('.size-box').forEach(b => b.classList.remove(
                            'selected'));
                        this.classList.add('selected');

                        // Define o tamanho selecionado
                        tamanhoSelecionado = this.getAttribute('data-tamanho-id');
                        document.getElementById('tamanho_id').value = tamanhoSelecionado;

                        // Limpa seleção de cor anterior
                        corSelecionada = null;
                        document.getElementById('cor').value = '';

                        // Mostra as cores disponíveis para este tamanho
                        mostrarCoresParaTamanho(this);
                    });
                });

                function mostrarCoresParaTamanho(tamanhoBox) {
                    const coresContainer = document.getElementById('cores-container');
                    const coresOpcoes = document.getElementById('cores-opcoes');

                    // Limpa cores anteriores
                    coresOpcoes.innerHTML = '';

                    // Obtém as cores do atributo data-cores
                    const cores = JSON.parse(tamanhoBox.getAttribute('data-cores'));

                    if (cores && cores.length > 0) {
                        // Cria os botões de cor
                        cores.forEach(cor => {
                            const colorBox = document.createElement('span');
                            colorBox.className = 'color-box';
                            colorBox.style.backgroundColor = cor.cor_hex;
                            colorBox.dataset.cor = cor.cor;
                            colorBox.title = cor.cor; // Tooltip com o nome da cor

                            colorBox.addEventListener('click', function() {
                                document.querySelectorAll('.color-box').forEach(b => b.classList.remove(
                                    'selected'));
                                this.classList.add('selected');
                                corSelecionada = this.dataset.cor;
                                document.getElementById('cor').value = corSelecionada;
                            });

                            coresOpcoes.appendChild(colorBox);
                        });
                        coresContainer.style.display = 'block';
                    } else {
                        coresContainer.style.display = 'none';
                    }
                }
            });

            function toggleTabelaMedidas() {
                const tabela = document.querySelector('.tabela-medidas');
                const botao = document.querySelector('.tabela-toggle');
                const nota = document.getElementById('medidas-nota');

                tabela.classList.toggle('show');
                botao.classList.toggle('active');

                if (tabela.classList.contains('show')) {
                    nota.style.display = 'block';
                    botao.textContent = 'Ocultar Tabela de Medidas';
                } else {
                    nota.style.display = 'none';
                    botao.textContent = 'Ver Tabela de Medidas';
                }

                // Adiciona ou remove o ícone de seta
                if (!botao.querySelector('i')) {
                    const icon = document.createElement('i');
                    icon.className = 'fas fa-chevron-down';
                    botao.appendChild(icon);
                }
            }

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
