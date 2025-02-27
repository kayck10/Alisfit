@extends('Layout.principal')

@section('content')
    <div class="container py-5">
        <h2 class="text-center mb-5 mt-5">Pagamento Aprovado</h2>

        <div class="alert alert-success">
            <h4>Obrigado pela sua compra!</h4>
            <p>O seu pagamento foi aprovado com sucesso. Você receberá um e-mail com os detalhes do seu pedido.</p>
            <p><strong>Resumo do Pedido:</strong></p>
            <ul>
                <li><strong>ID do Pedido:</strong> {{ $pedido->id }}</li>
                <li><strong>Total:</strong> R$ {{ number_format($pedido->total_com_desconto, 2, ',', '.') }}</li>
            </ul>
            <p>Aguarde a confirmação do envio e o rastreamento do seu pedido em breve!</p>
            <a href="" class="btn btn-primary">Ver todos os pedidos</a>
        </div>
    </div>
@endsection
