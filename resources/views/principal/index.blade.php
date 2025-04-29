@extends('Layout.principal')

@section('title', config('app.name'))

@section('content')

    <style>
        .parallax {
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 40px 10px;
            text-align: center;
        }

        .parallax-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            max-width: 90%;
            /* Para melhor responsividade */
        }

        /* Ajuste de tamanho do texto */
        .parallax-overlay p {
            font-size: 1.1rem;
            line-height: 1.6;
        }

        /* Esconder conteúdo extra inicialmente */
        .hidden {
            display: none;
        }

        /* Responsividade para telas menores */
        @media (max-width: 768px) {
            .parallax {
                background-attachment: scroll;
                min-height: auto;
                padding: 20px;
            }

            .parallax-overlay {
                max-width: 95%;
                padding: 15px;
            }

            .parallax-overlay p {
                font-size: 1rem;
            }
        }

        /* Botão animado */
        #btn-leia-mais {
            transition: background 0.3s ease-in-out;
        }

        #btn-leia-mais:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }

        .products-carousel {
            position: relative;
            padding: 0 40px;
            margin: 30px 0;
        }

        .products-slider {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            padding: 20px 0;
            scrollbar-width: none;
            /* Para Firefox */
        }

        .products-slider::-webkit-scrollbar {
            display: none;
            /* Para Chrome/Safari */
        }

        .product-slide {
            flex: 0 0 auto;
            width: 280px;
            scroll-snap-align: start;
            transition: transform 0.3s ease;
        }

        .product-slide:hover {
            transform: translateY(-5px);
        }

        .product-slide img {
            width: 100%;
            height: 350px;
            object-fit: cover;
            border-radius: 8px;
        }

        .carousel-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            border: none;
        }

        .carousel-nav.prev {
            left: 0;
        }

        .carousel-nav.next {
            right: 0;
        }

        .carousel-nav:hover {
            background: rgba(0, 0, 0, 0.8);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .product-slide {
                width: 220px;
            }

            .product-slide img {
                height: 280px;
            }
        }

        @media (max-width: 576px) {
            .product-slide {
                width: 180px;
            }

            .product-slide img {
                height: 230px;
            }
        }

        .product-card {
            flex: 0 0 auto;
            width: 340px;
            scroll-snap-align: start;
            border-radius: 10px;
            text-align: center;
            padding: 15px;
        }

        .product-card img {
            width: 100%;
            height: 450px;
            border-radius: 10px;
            object-fit: cover;
        }

        #carouselExample {
            margin-bottom: -5px !important;
        }

        #promo-ticker {
            background-color: black;
            color: white;
            padding: 1px 0;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
            width: 100%;
            margin-top: -5px !important;
            position: relative;
            z-index: 10;
        }

        .ticker-wrap {
            width: 100%;
            overflow: hidden;
            position: relative;
        }

        .ticker {
            display: flex;
            gap: 50px;
            font-size: 16px;
            font-weight: bold;
            animation: tickerMove 15s linear infinite;
        }

        .ticker span {
            display: inline-block;
            padding: 0 30px;
        }

        #mais-vendidos {
            text-align: center;
            padding: 50px 20px;
            background-color: #f5f5f5;
        }

        .mais-vendidos-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .mais-vendidos-item {
            position: relative;
            overflow: hidden;
            border-radius: 10px;
            cursor: pointer;
            flex: 1 1 calc(50% - 20px);
            max-width: calc(50% - 20px);
        }

        .mais-vendidos-item img {
            width: 100%;
            height: 700px;
            object-fit: cover;
            border-radius: 10px;
            transition: transform 0.3s ease, opacity 0.3s ease;

        }


        .mais-vendidos-item:hover img {
            transform: scale(1.1);
            opacity: 0.8;
        }

        .overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            padding: 15px 25px;
            font-size: 1.5rem;
            font-weight: bold;
            border-radius: 5px;
            text-transform: uppercase;
            transition: background 0.3s ease-in-out;
        }

        /* Responsividade */
        @media (max-width: 1024px) {
            .mais-vendidos-item {
                flex: 1 1 calc(50% - 10px);
                max-width: calc(50% - 10px);
            }
        }

        @media (max-width: 768px) {
            .mais-vendidos-item img {
                height: 400px;
                object-fit: cover;
                transition: transform 0.3s ease, opacity 0.3s ease;
            }
        }



        @media (max-width: 768px) {
            .mais-vendidos-item {
                flex: 1 1 100%;
                max-width: 100%;
            }
        }

        @keyframes tickerMove {
            from {
                transform: translateX(100%);
            }

            to {
                transform: translateX(-100%);
            }
        }

        /* Responsividade para telas menores */
        @media (max-width: 1024px) {
            .product {
                flex: 1 1 calc(50% - 10px);
                /* Em telas menores, exibe 2 por linha */
                max-width: calc(50% - 10px);
            }
        }

        @media (max-width: 768px) {
            .product {
                flex: 1 1 calc(50% - 10px);
                /* Em telas menores, exibe 2 por linha */
                max-width: calc(50% - 10px);
            }
        }

        @media (max-width: 480px) {
            .product {
                flex: 1 1 100%;
                /* Em celulares, exibe 1 por linha */
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .carousel-products {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
            }

            .product-card {
                flex: 1 1 calc(50% - 10px);
                max-width: calc(50% - 10px);
            }

            @media (max-width: 768px) {
                .product-card img {
                    width: 100%;
                    height: 180px;
                    aspect-ratio: 4 / 5;
                    border-radius: 10px;
                    object-fit: cover;
                }
            }

        }

        .carousel-item:nth-child(2) img {
            object-position: top center;
        }

        .carousel-inner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center center;
        }

        /* Telas menores */
        @media (max-width: 768px) {
            .carousel-inner {
                height: 31vh;
            }

            .carousel-inner img {
                object-position: top center;
            }

            .carousel-item:nth-child(2) img {
                object-position: 20% center;
                /* ou outro valor que funcione melhor pra você */
            }
        }
    </style>


    <!-- Carrossel Fullscreen -->
    <div id="carouselExample" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/banner/IMGBANNER.jfif') }}" class="d-block w-100" alt="Banner 1">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/banner/banner002.JPG') }}" class="d-block w-100" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="{{ asset('images/banner/banner003.JPG') }}" class="d-block w-100" alt="Banner 3">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
        </button>
    </div>

    <section id="promo-ticker">
        <div class="ticker-wrap">
            <div class="ticker">
                <span>COMPRE AGORA E PARCELE EM ATÉ 10X</span>
                <span>FRETE GRÁTIS A PARTIR DE R$ 250</span>
                <span>COMPRE AGORA E PARCELE EM ATÉ 10X</span>
                <span>FRETE GRÁTIS A PARTIR DE R$ 250</span>
                <span>COMPRE AGORA E PARCELE EM ATÉ 10X</span>
                <span>FRETE GRÁTIS A PARTIR DE R$ 250</span>
                <span>COMPRE AGORA E PARCELE EM ATÉ 10X</span>
                <span>FRETE GRÁTIS A PARTIR DE R$ 250</span>
            </div>
        </div>
    </section>



    <section id="product-section">
        <div class="container">
            <h1 class="text-center mb-4">Produtos em destaque</h1>

            <div class="products-carousel">
                <button class="carousel-nav prev" onclick="scrollProducts(-1)">❮</button>

                <div class="products-slider" id="productsSlider">
                    @foreach ($produtos as $produto)
                        <div class="product-slide">
                            <a href="{{ route('produto.detalhes', $produto->id) }}">
                                @if ($produto->imagens->isNotEmpty())
                                    <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($produto) }}"
                                        alt="{{ $produto->nome }}" class="img-fluid">
                                @else
                                    <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão" class="img-fluid">
                                @endif
                            </a>
                            <div class="product-details mt-2 text-center">
                                <h5 class="text-secondary">{{ $produto->nome }}</h5>
                                <strong>
                                    <p class="text-dark">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                                </strong>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button class="carousel-nav next" onclick="scrollProducts(1)">❯</button>
            </div>
        </div>
    </section>
    </section>


    <!-- Seção Mais Vendidos -->
    <section id="mais-vendidos">
        <h4 class="section-title mb-5"><strong>MAIS VENDIDOS</strong></h4>
        <div class="mais-vendidos-container">
            <div class="mais-vendidos-item">
                <a href="{{ route('produtos.ofertasM') }}">
                    <img src="{{ asset('images/banner/ofMas.jpeg') }}" alt="Ofertas Masculinas">
                    <div class="overlay">Ofertas Masculinas</div>
                </a>
            </div>
            <div class="mais-vendidos-item">
                <a href="{{ route('produtos.ofertasF') }}">
                    <img src="{{ asset('images/banner/ofFem.JPG') }}" alt="Ofertas Femininas">
                    <div class="overlay">Ofertas Femininas</div>
                </a>
            </div>
        </div>
    </section>



    <!-- Seção de Parallax -->
    <section class="parallax" style="background-image: url('{{ asset('images/banner/IMG_4680.JPG') }}');">
        <div class="parallax-overlay">
            <h2 class="text-white fw-bold">One day or Day one</h2>

            <p class="text-white mt-2">
                A <strong>ALIS</strong> é uma marca que redefine a exclusividade no mundo da moda esportiva.
                Com um foco inabalável na qualidade e no design único, cada peça da ALIS é
                produzida em <strong>edições limitadas</strong>, garantindo que cada item seja especial para
                seus usuários. A produção restrita não apenas eleva o status de cada vestuário,
                mas também fomenta uma conexão mais íntima entre a marca e seus clientes,
                que valorizam a <strong>distinção e a singularidade</strong> em suas escolhas.
            </p>

            <p class="text-white mt-2">
                A exclusividade da ALIS vai além do número limitado de peças produzidas. O
                design de cada item reflete uma fusão perfeita de <strong>inovação e elegância</strong>, com
                linhas que respeitam tanto a funcionalidade quanto a estética moderna. O
                logotipo da marca, com seus traços retos e curvas sutis, simboliza a
                <strong>modernidade e leveza</strong> de um estilo de vida único.
            </p>
        </div>
    </section>




    <script>
        // Função para navegação do carrossel
        function scrollProducts(direction) {
            const slider = document.getElementById('productsSlider');
            const scrollAmount = 300; // Ajuste conforme necessário
            const maxScroll = slider.scrollWidth - slider.clientWidth;

            // Verifica se está no final e indo para a direita
            if (direction > 0 && slider.scrollLeft >= maxScroll - 10) {
                // Volta suavemente para o início
                slider.scrollTo({
                    left: 0,
                    behavior: 'smooth'
                });
            }
            // Verifica se está no início e indo para a esquerda
            else if (direction < 0 && slider.scrollLeft <= 10) {
                // Vai suavemente para o final
                slider.scrollTo({
                    left: maxScroll,
                    behavior: 'smooth'
                });
            } else {
                // Scroll normal
                slider.scrollBy({
                    left: direction * scrollAmount,
                    behavior: 'smooth'
                });
            }
        }

        // Opcional: Auto-scroll infinito para dispositivos móveis
        if (window.innerWidth <= 768) {
            const slider = document.getElementById('productsSlider');
            let scrollInterval;
            let isScrolling = false;

            function autoScroll() {
                if (isScrolling) return;

                isScrolling = true;
                const maxScroll = slider.scrollWidth - slider.clientWidth;
                const currentScroll = slider.scrollLeft;

                if (currentScroll >= maxScroll - 10) {
                    // Volta suavemente para o início
                    slider.scrollTo({
                        left: 0,
                        behavior: 'smooth'
                    }).then(() => {
                        isScrolling = false;
                    });
                } else {
                    // Scroll normal
                    slider.scrollBy({
                        left: 300,
                        behavior: 'smooth'
                    }).then(() => {
                        isScrolling = false;
                    });
                }
            }

            function startAutoScroll() {
                scrollInterval = setInterval(autoScroll, 3000);
            }

            function stopAutoScroll() {
                clearInterval(scrollInterval);
            }

            // Inicia o auto-scroll
            startAutoScroll();

            // Pausa quando o mouse está sobre o carrossel
            slider.addEventListener('mouseenter', stopAutoScroll);
            slider.addEventListener('mouseleave', startAutoScroll);

            // Pausa quando o usuário interage com o touch
            slider.addEventListener('touchstart', stopAutoScroll);
            slider.addEventListener('touchend', startAutoScroll);
        }
    </script>

@endsection
