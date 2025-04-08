@extends('Layout.principal')

@section('content')
<main>

    <!-- Banner Principal -->
    <section class="banner position-relative d-flex align-items-center justify-content-center text-center text-white"
        style="background: url('{{ asset('images/banner/banner003.JPG') }}') center/cover no-repeat; height: 600px;">
        <div class="overlay position-absolute w-100 h-100" style="background: rgba(0, 0, 0, 0.6); top: 0; left: 0;"></div>
        <div class="position-relative z-1">
            <h1 class="display-3 fw-bold animate__animated animate__fadeInDown">Políticas de Trocas e Devoluções</h1>
        </div>
    </section>

    <!-- Conteúdo da Política -->
    <section class="py-5 text-white" style="background: linear-gradient(to right, #b2b3be, #72787d);">
        <div class="container">
            <div class="quem-somos-text mx-auto" style="max-width: 850px;">
                <h2 class="mb-4 fw-bold"><i class="bi bi-arrow-repeat me-2"></i>Política de Trocas e Devoluções</h2>
                <p>Nosso compromisso é garantir sua satisfação com as peças exclusivas da <strong>Alis</strong>. Caso precise realizar uma troca ou devolução, estamos aqui para ajudar! Confira abaixo os detalhes da nossa política:</p>

                <ol class="mt-4">
                    <li class="mb-3">
                        <strong>Prazos para Trocas e Devoluções:</strong><br>
                        Você pode solicitar a troca ou devolução de um produto no prazo de até <strong>30 dias corridos</strong> após o recebimento. Para produtos com <strong>defeito de fabricação</strong>, o prazo é de até 90 dias.
                    </li>

                    <li class="mb-3">
                        <strong>Condições para Trocas e Devoluções:</strong>
                        <ul class="mt-2">
                            <li>Produto não deve ter sido usado, lavado ou alterado.</li>
                            <li>Deve estar com embalagem original, etiquetas e acessórios (se houver).</li>
                        </ul>
                    </li>

                    <li class="mb-3">
                        <strong>Motivos para Troca ou Devolução:</strong><br>
                        Tamanho inadequado, defeito ou arrependimento (em até 7 dias conforme o Código de Defesa do Consumidor).
                    </li>

                    <li class="mb-3">
                        <strong>Como Solicitar:</strong><br>
                        Envie um e-mail ou WhatsApp com o número do pedido, a peça e o motivo da troca ou devolução.
                    </li>

                    <li class="mb-3">
                        <strong>Reembolso:</strong><br>
                        Será realizado após análise e aprovação do produto devolvido.
                    </li>

                    <li class="mb-3">
                        <strong>Peças Exclusivas e Limitadas:</strong><br>
                        Caso o item não esteja mais disponível, oferecemos reembolso ou possibilidade de escolha de uma nova peça.
                    </li>
                </ol>
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

    ol {
        padding-left: 1.2rem;
    }

    ul {
        list-style-type: disc;
        padding-left: 1.5rem;
    }
</style>

<!-- Animações e Ícones -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endsection
