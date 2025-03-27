@extends('Layout.principal')

<style>
    /* Adicionando margens no topo e no fundo */
    .container {
        margin-top: 50px;
        margin-bottom: 50px;
    }

    /* Estilo para o banner */
    .banner img {
        max-height: 400px;
        object-fit: cover; /* Ajuste a altura conforme necessário */
    }

    /* Estilo para os textos nos botões de dropdown */
    .accordion .btn-link {
        font-weight: bold;
        text-decoration: none;
        color: black; /* Definindo cor preta para o texto */
    }

    /* Efeito ao passar o mouse nos botões */
    .accordion .btn-link:hover {
        text-decoration: underline;
    }

    /* Margem inferior para os cards */
    .card.mb-3 {
        margin-bottom: 20px;
    }
</style>

@section('content')
    <!-- Banner -->
    <div class="banner">
        <img src="{{ asset('images/banner/9.png') }}" alt="Banner da Loja" class="img-fluid w-100">
    </div>

    <div class="container my-5">
        <h2 class="text-center mb-4">{{ __('Informações da Loja') }}</h2>

        <!-- Dropdowns com conteúdo dinâmico -->
        <div class="accordion" id="informacoesAccordion">

            <!-- Informações de entrega -->
            <div class="card mb-3">
                <div class="card-header" id="headingDelivery">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseDelivery"
                            aria-expanded="true" aria-controls="collapseDelivery">
                            {{ __('Informações de entrega') }}
                        </button>
                    </h5>
                </div>

                <div id="collapseDelivery" class="collapse" aria-labelledby="headingDelivery" data-parent="#informacoesAccordion">
                    <div class="card-body">
                        <h6>Informações de Entrega - Alis</h6>
                        Bem-vindo à Alis! Abaixo você encontra todas as informações necessárias sobre o nosso processo de entrega:
                        <ol>
                            <li><strong>Prazo de Entrega:</strong>
                                <ul>
                                    <li>O prazo de entrega varia de acordo com a sua localidade e a modalidade de envio escolhida no momento da compra.</li>
                                    <li>Pedidos feitos e aprovados até as 14h serão processados no mesmo dia. Caso contrário, o processamento será feito no próximo dia útil.</li>
                                    <li><strong>Prazo estimado:</strong>
                                        <ul>
                                            <li>Capitais: 7 a 12 dias úteis</li>
                                            <li>Interior: 12 a 17 dias úteis</li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><strong>Cálculo do Frete:</strong> O valor do frete será calculado automaticamente no checkout com base no seu CEP e na opção de envio selecionada.</li>
                            <li><strong>Rastreamento:</strong> Assim que seu pedido for enviado, você receberá um e-mail com o código de rastreamento e instruções de como acompanhar sua entrega.</li>
                            <li><strong>Entregas e Horários:</strong> As entregas são feitas de segunda a sexta-feira, das 8h às 18h. Em algumas regiões, também oferecemos entrega aos sábados.</li>
                            <li><strong>Tentativas de Entrega:</strong> Serão realizadas até 3 tentativas de entrega. Caso não haja sucesso, o pedido retornará para o nosso centro de distribuição e entraremos em contato para instruções de reenvio.</li>
                            <li><strong>Retirada em Ponto Físico:</strong> Para clientes que desejam retirar pessoalmente, oferecemos a opção de retirada em nossa loja física localizada em Fortaleza (Avenida Zezé Dioguinho, 2771).</li>
                            <li><strong>Regiões Atendidas:</strong> Atualmente, realizamos entregas em todo o território nacional.</li>
                            <li><strong>Dúvidas ou Problemas com a Entrega:</strong> Caso tenha dúvidas ou enfrente algum problema com a entrega, entre em contato com a nossa equipe de atendimento ao cliente por e-mail ou WhatsApp, que teremos prazer em ajudar.</li>
                        </ol>
                        Agradecemos por escolher a Alis!
                    </div>
                </div>
            </div>

            <!-- Política de Trocas e Devoluções -->
            <div class="card mb-3">
                <div class="card-header" id="headingPrivacy">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsePrivacy"
                            aria-expanded="false" aria-controls="collapsePrivacy">
                            {{ __('Política de Trocas e Devoluções') }}
                        </button>
                    </h5>
                </div>

                <div id="collapsePrivacy" class="collapse" aria-labelledby="headingPrivacy" data-parent="#informacoesAccordion">
                    <div class="card-body">
                        <h6>Política de Trocas e Devoluções</h6>
                        Nosso compromisso é garantir sua satisfação com as peças exclusivas da Alis. Caso precise realizar uma troca ou devolução, estamos aqui para ajudar! Confira abaixo os detalhes de nossa política:
                        <ol>
                            <li><strong>Prazos para Trocas e Devoluções:</strong> Você pode solicitar a troca ou devolução de um produto no prazo de até 30 dias corridos, contados a partir da data de recebimento da compra. Para produtos com defeito de fabricação, o prazo é de até 90 dias.</li>
                            <li><strong>Condições para Trocas e Devoluções:</strong> O produto deve estar nas seguintes condições:
                                <ul>
                                    <li>Não ter sido usado, lavado ou alterado de qualquer forma.</li>
                                    <li>Acompanhado da embalagem original, etiquetas e acessórios (se houver).</li>
                                </ul>
                            </li>
                            <li><strong>Motivos para Troca ou Devolução:</strong> Tamanho, Defeito ou Arrependimento (direito de arrependimento dentro de 7 dias).</li>
                            <li><strong>Como Solicitar Troca ou Devolução:</strong> Entre em contato conosco via e-mail ou WhatsApp informando o número do pedido, a peça que deseja trocar ou devolver e o motivo.</li>
                            <li><strong>Reembolso:</strong> O reembolso será realizado após análise do produto devolvido.</li>
                            <li><strong>Peças Exclusivas e Limitadas:</strong> Caso o item solicitado para troca não esteja mais disponível, oferecemos a possibilidade de comprar uma nova peça ou reembolso do valor.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Fale Conosco -->
            <div class="card mb-3">
                <div class="card-header" id="headingContact">
                    <h5 class="mb-0">
                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseContact"
                            aria-expanded="false" aria-controls="collapseContact">
                            {{ __('Fale Conosco') }}
                        </button>
                    </h5>
                </div>

                <div id="collapseContact" class="collapse" aria-labelledby="headingContact" data-parent="#informacoesAccordion">
                    <div class="card-body">
                        <p><strong>E-mail:</strong> alisoriginalfitness@gmail.com</p>
                        <p><strong>Whatsapp:</strong> +55 (85) 92001-4169</p>
                        <p><strong>Horário de atendimento:</strong> Segunda a sábado, 08:00 - 18:00</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

<!-- Scripts do Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
