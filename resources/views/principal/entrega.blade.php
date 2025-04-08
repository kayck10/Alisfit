@extends('Layout.principal')

@section('content')
<main>

    <!-- Banner Principal -->
    <section class="banner" style="background: url('{{ asset('images/banner/banner003.JPG') }}') center/cover no-repeat;">
        <div class="overlay d-flex align-items-center justify-content-center text-center">
            <h1 class="text-white display-3 fw-bold slide-in-left">Informações de Entrega</h1>
        </div>
    </section>

    <section class="info-entrega text-white py-5">
        <div class="container fade-in">
            <div class="info-entrega-text mx-auto">
                <h3 class="mb-4 text-uppercase fw-bold">Informações de Entrega - Alis</h3>
                <p class="mb-4">Bem-vindo à Alis! Abaixo você encontra todas as informações necessárias sobre o nosso processo de entrega:</p>
                <ol class="list-group list-group-numbered">
                    <li class="list-group-item bg-transparent border-0 text-white">
                        <strong>Prazo de Entrega:</strong>
                        <ul>
                            <li>O prazo varia de acordo com a localidade e modalidade de envio.</li>
                            <li>Pedidos aprovados até as 14h são processados no mesmo dia útil.</li>
                            <li><strong>Estimativa:</strong>
                                <ul>
                                    <li><i class="bi bi-geo-alt-fill text-warning"></i> Capitais: 7 a 12 dias úteis</li>
                                    <li><i class="bi bi-geo-alt-fill text-warning"></i> Interior: 12 a 17 dias úteis</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li class="list-group-item bg-transparent border-0 text-white">
                        <strong>Cálculo do Frete:</strong> Automático no checkout com base no CEP e opção escolhida.
                    </li>
                    <li class="list-group-item bg-transparent border-0 text-white">
                        <strong>Rastreamento:</strong> Enviamos um e-mail com o código e instruções de acompanhamento.
                    </li>
                    <li class="list-group-item bg-transparent border-0 text-white">
                        <strong>Entregas e Horários:</strong> Segunda a sexta, das 8h às 18h. Em algumas regiões, também aos sábados.
                    </li>
                    <li class="list-group-item bg-transparent border-0 text-white">
                        <strong>Tentativas de Entrega:</strong> Até 3 tentativas. Se não houver sucesso, entraremos em contato.
                    </li>
                    <li class="list-group-item bg-transparent border-0 text-white">
                        <strong>Regiões Atendidas:</strong> Todo o território nacional.
                    </li>
                    <li class="list-group-item bg-transparent border-0 text-white">
                        <strong>Dúvidas ou Problemas:</strong> Fale conosco por e-mail ou WhatsApp. Teremos prazer em ajudar!
                    </li>
                </ol>
                <p class="mt-4 text-center fw-semibold">Agradecemos por escolher a <span class="text-warning">Alis</span>!</p>
            </div>
        </div>
    </section>

    <style>
        .banner {
            width: 100%;
            height: 700px;
            position: relative;
        }

        .banner .overlay {
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
        }

        .info-entrega {
            background: linear-gradient(to right, #b2b3be, #72787d);
        }

        .info-entrega-text {
            max-width: 850px;
            font-size: 1.1rem;
            line-height: 1.8;
        }

        /* Animações */
        .fade-in {
            opacity: 0;
            animation: fadeIn 1s forwards;
        }

        .slide-in-left {
            transform: translateX(-50px);
            opacity: 0;
            animation: slideInLeft 1s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Estilização para lista numerada */
        .list-group-numbered > .list-group-item::before {
            color: #ffc107;
            font-weight: bold;
        }
    </style>

</main>
@endsection
