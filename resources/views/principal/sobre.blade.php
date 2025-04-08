@extends('Layout.principal')

@section('content')
<main>

    <!-- Banner Principal -->
    <section class="banner" style="background: url('{{ asset('images/banner/banner003.JPG') }}') center/cover no-repeat;">
        <div class="overlay d-flex align-items-center justify-content-center">
            <h1 class="text-white display-3 fw-bold fade-in">Quem somos</h1>
        </div>
    </section>

    <!-- Seção Quem Somos (Com fundo azul) -->
    <section class="quem-somos text-white py-5">
        <div class="container">
            <div class="quem-somos-text fade-in">
                <p class="slide-in-left">Na Alis, acreditamos que o conforto e a criatividade andam de mãos dadas. Nossa equipe é formada por pessoas apaixonadas por criar uma experiência única no universo da moda fitness.</p>

                <p class="slide-in-right">Com um olhar atento para o design, priorizamos a simplicidade, exclusividade e funcionalidade em cada peça que desenvolvemos. Trabalhamos com um conceito de exclusividade: a cada lançamento, criamos peças limitadas e inovamos em seus conceitos.</p>

                <p class="slide-in-left">Esse compromisso nos permite sempre inovar, trazendo as tendências mais atuais para nossas coleções e garantindo que cada item que você adquire é realmente especial e único.</p>

                <p class="slide-in-right">Desde o início, nosso objetivo tem sido ir além de tendências passageiras. Queremos oferecer roupas que inspirem confiança e liberdade para quem busca estar em movimento, sem abrir mão do estilo e da originalidade.</p>

                <p class="slide-in-left">Nossa equipe de designers e especialistas trabalha incansavelmente para garantir que cada peça seja cuidadosamente pensada, levando em consideração o que é mais importante para você: conforto, qualidade, exclusividade e um toque de autenticidade.</p>

                <p class="slide-in-right">Juntos, estamos comprometidos em revolucionar o mercado de moda fitness, criando roupas que sejam mais do que simples peças de treino — elas são uma extensão da sua personalidade, feitas para acompanhar cada passo da sua jornada.</p>

                <p class="zoom-in">Alis não é apenas uma marca de roupas. É uma comunidade de pessoas criativas, ousadas e apaixonadas pelo esporte. Estamos aqui para apoiar sua trajetória com peças que se ajustam ao seu estilo de vida.</p>
            </div>
        </div>
    </section>

<style>
    /* Banner Principal */
    .banner {
        width: 100%;
        height: 700px;
        position: relative;
    }

    @media (max-width: 768px) {
        .banner {
            height: 400px;
        }

        .banner h1 {
            font-size: 2rem;
        }
    }

    .banner .overlay {
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
    }

    /* Quem Somos - Fundo Azul */
    .quem-somos {
        background: linear-gradient(to right, #b2b3be, #72787d);
    }

    .quem-somos-text {
        text-align: left;
        max-width: 800px;
        font-size: 1.1rem;
        line-height: 1.6;
        margin: 0 auto;
    }

    /* Animações */
    .fade-in {
        opacity: 0;
        animation: fadeIn 1s ease-in forwards;
    }
    .slide-in-left {
        opacity: 0;
        transform: translateX(-50px);
        animation: slideInLeft 1s ease-in forwards;
    }
    .slide-in-right {
        opacity: 0;
        transform: translateX(50px);
        animation: slideInRight 1s ease-in forwards;
    }
    .zoom-in {
        opacity: 0;
        transform: scale(0.8);
        animation: zoomIn 1s ease-in forwards;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }
    @keyframes slideInLeft {
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes slideInRight {
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes zoomIn {
        to { transform: scale(1); opacity: 1; }
    }
</style>

@endsection
