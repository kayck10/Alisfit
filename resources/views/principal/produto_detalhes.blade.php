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
        height: 800px;
        display: flex;
        justify-content: center;
        align-items: center;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .img-produto-wrapper {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .img-produto {
        position: absolute;
        width: 100%;
        height: 100%;
        object-fit: contain;
        transition: transform 0.5s ease;
        border-radius: 10px;
    }

    .img-produto.active {
        opacity: 1;
        z-index: 1;
    }

    .img-produto.hidden {
        opacity: 0;
        z-index: 0;
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
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .tabela-toggle {
        background-color: #f8f9fa;
        border: none;
        color: #333;
        padding: 12px 20px;
        font-size: 16px;
        width: 100%;
        text-align: left;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }

    .tabela-toggle:hover {
        background-color: #e9ecef;
    }

    #tabela-medidas-content {
        display: none;
        padding: 20px;
        background: #fff;
    }

    .tabela-medidas {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
        animation: fadeIn 0.3s ease;
    }

    .tabela-medidas th,
    .tabela-medidas td {
        border: 1px solid #e0e0e0;
        padding: 12px;
        text-align: center;
    }

    .tabela-medidas th {
        background-color: #f5f5f5;
        font-weight: 600;
    }

    .tabela-medidas tr:nth-child(even) {
        background-color: #fafafa;
    }

    .tabela-medidas tr:hover {
        background-color: #f1f1f1;
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
            height: 500px;
        }

        .img-produto {
            max-width: 90%;
            height: 700px;
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
                        data-image-index="{{ $loop->index }}"
                        onclick="trocarImagemPrincipal('{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($tempProduto) }}', {{ $loop->index }})">
                @endforeach
            </div>

            <!-- Imagem principal com container para swipe -->
            <div class="img-produto-container">
                <div class="img-produto-wrapper" id="img-produto-wrapper">
                    @foreach ($produto->imagens as $imagem)
                        @php
                            $tempProduto = new stdClass();
                            $tempProduto->imagens = collect([$imagem]);
                        @endphp
                        <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($tempProduto) }}"
                            alt="{{ $produto->nome }}"
                            class="img-produto @if ($loop->first) active @else hidden @endif"
                            data-image-index="{{ $loop->index }}">
                    @endforeach
                </div>
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

                @if ($produto->medidas->count() > 0)
                    <div class="tabela-container">
                        <button class="tabela-toggle" onclick="toggleTabelaMedidas()">
                            <span>
                                <i class="fas fa-ruler-combined me-2"></i>Tabela de Medidas
                            </span>
                            <i class="fas fa-chevron-down"></i>
                        </button>

                        <div id="tabela-medidas-content">
                            <table class="table table-bordered tabela-medidas">
                                <thead>
                                    <tr>
                                        <th>Tamanho</th>
                                        <th>Tórax (cm)</th>
                                        <th>Cintura (cm)</th>
                                        <th>Quadril (cm)</th>
                                        <th>Comprimento (cm)</th>
                                        <th>Altura (cm)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($produto->medidas->sortBy('tamanho') as $medida)
                                        <tr>
                                            <td>{{ $medida->tamanho }}</td>
                                            <td>{{ $medida->torax ?? '-' }}</td>
                                            <td>{{ $medida->cintura ?? '-' }}</td>
                                            <td>{{ $medida->quadril ?? '-' }}</td>
                                            <td>{{ $medida->comprimento ?? '-' }}</td>
                                            <td>{{ $medida->altura ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @if ($produto->medidas->first()->observacoes)
                                <div class="alert alert-info mt-3">
                                    <strong>Observações:</strong> {{ $produto->medidas->first()->observacoes }}
                                </div>
                            @endif

                            <p class="text-muted"><small>* Medidas podem variar em até 2cm.</small></p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
        <hr class="">

        <div class="upsell-container mb-4">
            <h5 class="mb-4 text-center"><strong>VOCÊ TAMBÉM VAI GOSTAR</strong></h5>
            <div class="upsell-list">
                @foreach ($produtosRelacionados as $relacionado)
                    <div class="upsell-item">
                        <a href="{{ route('produto.detalhes', $relacionado->id) }}">
                            <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($relacionado) }}"
                                alt="{{ $relacionado->nome }}" class="upsell-image">
                        </a>
                        <h5 class="mt-2">{{ $relacionado->nome }}</h5>
                        <p>R$ {{ number_format($relacionado->preco, 2, ',', '.') }}</p>
                        <a href="{{ route('produto.detalhes', $relacionado->id) }}" class="btn btn-dark btn-sm">Ver
                            Detalhes</a>
                    </div>
                @endforeach
            </div>
        </div>

        <script>
            // Variáveis para controle do swipe
            let currentImageIndex = 0;
            let startX = 0;
            let endX = 0;
            const images = document.querySelectorAll('.img-produto');
            const thumbnails = document.querySelectorAll('.miniatura');
            const wrapper = document.getElementById('img-produto-wrapper');

            // Função para trocar imagem
            function showImage(index) {
                // Esconde todas as imagens
                images.forEach(img => {
                    img.classList.add('hidden');
                    img.classList.remove('active');
                });

                // Mostra a imagem selecionada
                images[index].classList.remove('hidden');
                images[index].classList.add('active');

                // Atualiza a miniatura ativa
                thumbnails.forEach(thumb => {
                    thumb.classList.remove('active');
                    if (parseInt(thumb.getAttribute('data-image-index')) === index) {
                        thumb.classList.add('active');
                    }
                });

                currentImageIndex = index;
            }

            // Função modificada para trocar imagem principal
            function trocarImagemPrincipal(src, index) {
                showImage(index);
            }

            // Eventos para detectar swipe em dispositivos touch
            wrapper.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
            }, false);

            wrapper.addEventListener('touchmove', (e) => {
                endX = e.touches[0].clientX;
            }, false);

            wrapper.addEventListener('touchend', () => {
                if (startX - endX > 50 && currentImageIndex < images.length - 1) {
                    // Swipe para a esquerda - próxima imagem
                    showImage(currentImageIndex + 1);
                } else if (endX - startX > 50 && currentImageIndex > 0) {
                    // Swipe para a direita - imagem anterior
                    showImage(currentImageIndex - 1);
                }
            }, false);

            // Eventos para detectar swipe com mouse (para desktop)
            wrapper.addEventListener('mousedown', (e) => {
                startX = e.clientX;
                e.preventDefault(); // Previne seleção de texto durante o arrasto
            }, false);

            wrapper.addEventListener('mousemove', (e) => {
                if (startX) {
                    endX = e.clientX;
                }
            }, false);

            wrapper.addEventListener('mouseup', () => {
                if (startX) {
                    if (startX - endX > 50 && currentImageIndex < images.length - 1) {
                        // Swipe para a esquerda - próxima imagem
                        showImage(currentImageIndex + 1);
                    } else if (endX - startX > 50 && currentImageIndex > 0) {
                        // Swipe para a direita - imagem anterior
                        showImage(currentImageIndex - 1);
                    }
                    startX = 0;
                    endX = 0;
                }
            }, false);

            // Código JavaScript existente
            document.addEventListener("DOMContentLoaded", function() {
                let tamanhoSelecionado = null;
                let corSelecionada = null;

                document.querySelectorAll('.size-box').forEach(box => {
                    box.addEventListener('click', function() {
                        document.querySelectorAll('.size-box').forEach(b => b.classList.remove(
                            'selected'));
                        this.classList.add('selected');

                        tamanhoSelecionado = this.getAttribute('data-tamanho-id');
                        document.getElementById('tamanho_id').value = tamanhoSelecionado;

                        corSelecionada = null;
                        document.getElementById('cor').value = '';

                        mostrarCoresParaTamanho(this);
                    });
                });

                function mostrarCoresParaTamanho(tamanhoBox) {
                    const coresContainer = document.getElementById('cores-container');
                    const coresOpcoes = document.getElementById('cores-opcoes');

                    coresOpcoes.innerHTML = '';

                    const cores = JSON.parse(tamanhoBox.getAttribute('data-cores'));

                    if (cores && cores.length > 0) {
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

                function validarSelecao() {
                    if (!tamanhoSelecionado || !corSelecionada) {
                        alert('Por favor, selecione um tamanho e uma cor antes de adicionar ao carrinho.');
                        return false;
                    }
                    return true;
                }

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

            function toggleTabelaMedidas() {
    const content = document.getElementById('tabela-medidas-content');
    const icon = document.querySelector('.tabela-toggle .fa-chevron-down');
    const table = document.querySelector('.tabela-medidas');

    if (content.style.display === 'none' || content.style.display === '') {
        content.style.display = 'block';
        table.style.display = 'table'; // Garante que a tabela será mostrada
        icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
    } else {
        content.style.display = 'none';
        icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
    }
}
        </script>
    @endsection
