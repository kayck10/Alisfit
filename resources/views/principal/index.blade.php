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

        .carousel-products {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 2px;
            padding: 20px;
            scrollbar-width: none;
            justify-content: center;
        }

        .carousel-products::-webkit-scrollbar {
            display: none;
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
            padding: 10px 0;
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
            /* Padrão: 2 itens por linha */
            max-width: calc(50% - 20px);
        }

        .mais-vendidos-item img {
            width: 100%;
            height: 600px;
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
                height: 200px;
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
    </style>

    <!-- Carrossel Fullscreen -->
    <div id="carouselExample" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{ asset('images/banner/banner00.JPG') }}" class="d-block w-100" alt="Banner 1">
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
                <span>FRETE GRÁTIS PARA TODO BRASIL</span>
                <span>COMPRE AGORA E PARCELE EM ATÉ 10X</span>
                <span>NOVIDADES TODA SEMANA</span>
                <span>DEVOLUÇÃO GARANTIDA EM ATÉ 7 DIAS</span>
            </div>
        </div>
    </section>



    <!-- Produtos em destaque -->
    <section id="product-section">
        <h1>Produtos em destaque</h1>
        <div class="carousel-products">
            @foreach ($produtos as $produto)
                <div class="product-card" id="product-{{ $produto->id }}">
                    <div class="product-image">
                        <a href="{{ route('produto.detalhes', $produto->id) }}">
                            @if ($produto->imagens->isNotEmpty())
                                <img src="{{ asset('storage/' . $produto->imagens->first()->imagem) }}"
                                    alt="{{ $produto->nome }}">
                            @else
                                <img src="{{ asset('images/banner/12.png') }}" alt="Imagem padrão">
                            @endif
                        </a>
                    </div>
                    <div class="product-details">
                        <h5 class="text-secondary">{{ $produto->nome }}</h5>
                        <strong>
                            <p class="text-dark">R$ {{ number_format($produto->preco, 2, ',', '.') }}</p>
                        </strong>
                    </div>
                </div>
            @endforeach
        </div>
    </section>


    <!-- Seção Mais Vendidos -->
    <section id="mais-vendidos">
        <h4 class="section-title mb-5"><strong>MAIS VENDIDOS</strong></h4>
        <div class="mais-vendidos-container">
            <div class="mais-vendidos-item">
                <img src="{{ asset('images/banner/ofMas.jpeg') }}" alt="Ofertas Masculinas">
                <div class="overlay">Ofertas Masculinas</div>
            </div>
            <div class="mais-vendidos-item">
                <img src="{{ asset('images/banner/ofFem.JPG') }}" alt="Ofertas Femininas">
                <div class="overlay">Ofertas Femininas</div>
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
        document.getElementById('btn-leia-mais').addEventListener('click', function() {
            let textoCompleto = document.getElementById('texto-completo');

            if (textoCompleto.style.display === 'none' || textoCompleto.style.display === '') {
                textoCompleto.style.display = 'block';
                this.innerText = 'Leia Menos';
            } else {
                textoCompleto.style.display = 'none';
                this.innerText = 'Leia Mais';
            }
        });
    </script>

@endsection
