@extends('Layout.principal')

<style>
    /* Adicionando margens no topo e no fundo */
    .container {
        margin-top: 50px;
        margin-bottom: 50px;
    }

    /* Estilo para o banner */
    .banner img {
        max-height: 400px; /* Ajuste a altura conforme necessário */
        object-fit: cover; /* Para garantir que a imagem se ajuste bem */
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
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseDelivery" aria-expanded="true" aria-controls="collapseDelivery">
                        {{ __('Informações de entrega') }}
                    </button>
                </h5>
            </div>

            <div id="collapseDelivery" class="collapse" aria-labelledby="headingDelivery" data-parent="#informacoesAccordion">
                <div class="card-body">
                    Aqui estão as informações de entrega da loja...
                </div>
            </div>
        </div>

        <!-- Política de Privacidade -->
        <div class="card mb-3">
            <div class="card-header" id="headingPrivacy">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapsePrivacy" aria-expanded="false" aria-controls="collapsePrivacy">
                        {{ __('Política de Privacidade') }}
                    </button>
                </h5>
            </div>

            <div id="collapsePrivacy" class="collapse" aria-labelledby="headingPrivacy" data-parent="#informacoesAccordion">
                <div class="card-body">
                    Aqui estão os detalhes sobre a política de privacidade...
                </div>
            </div>
        </div>

        <!-- Termos & Condições -->
        <div class="card mb-3">
            <div class="card-header" id="headingTerms">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTerms" aria-expanded="false" aria-controls="collapseTerms">
                        {{ __('Termos & Condições') }}
                    </button>
                </h5>
            </div>

            <div id="collapseTerms" class="collapse" aria-labelledby="headingTerms" data-parent="#informacoesAccordion">
                <div class="card-body">
                    Aqui estão os termos e condições da loja...
                </div>
            </div>
        </div>

        <!-- Fale Conosco -->
        <div class="card mb-3">
            <div class="card-header" id="headingContact">
                <h5 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseContact" aria-expanded="false" aria-controls="collapseContact">
                        {{ __('Fale Conosco') }}
                    </button>
                </h5>
            </div>

            <div id="collapseContact" class="collapse" aria-labelledby="headingContact" data-parent="#informacoesAccordion">
                <div class="card-body">
                    Aqui estão as informações para contato com a loja...
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
