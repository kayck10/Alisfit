@extends('Layout.principal')

@section('content')
<main>

    <!-- Banner Principal -->
    <section class="banner position-relative d-flex align-items-center justify-content-center text-center text-white"
        style="background: url('{{ asset('images/banner/banner003.JPG') }}') center/cover no-repeat; height: 600px;">
        <div class="overlay position-absolute w-100 h-100" style="background: rgba(0, 0, 0, 0.6); top: 0; left: 0;"></div>
        <div class="position-relative z-1">
            <h1 class="display-3 fw-bold animate__animated animate__fadeInDown">Fale Conosco</h1>
        </div>
    </section>

    <!-- Contato -->
    <section class="py-5 text-white" style="background: linear-gradient(to right, #b2b3be, #72787d);">
        <div class="container">
            <div class="quem-somos-text mx-auto" style="max-width: 700px;">
                <h2 class="mb-4 fw-bold"><i class="bi bi-chat-dots-fill me-2"></i>Entre em contato</h2>
                <p class="mb-3"><strong><i class="bi bi-envelope-fill me-2"></i>E-mail:</strong> alisoriginalfitness@gmail.com</p>
                <p class="mb-3"><strong><i class="bi bi-whatsapp me-2"></i>Whatsapp:</strong> +55 (85) 92001-4169</p>
                <p><strong><i class="bi bi-clock-fill me-2"></i>Horário de atendimento:</strong> Segunda a sábado, 08:00 - 18:00</p>
            </div>
        </div>
    </section>

</main>

<!-- Estilos adicionais -->
<style>
    .quem-somos-text {
        font-size: 1.1rem;
        line-height: 1.8;
    }

    @media (max-width: 768px) {
        .banner {
            height: 400px !important;
        }
    }
</style>

<!-- Animações (se desejar usar Animate.css, inclua no layout) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<!-- Ícones Bootstrap -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
