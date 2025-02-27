@extends('Layout.principal')

@section('content')
    <div class="container py-5">
        <h2 class="text-center mb-5 mt-5">Pagamento Recusado</h2>

        <div class="alert alert-danger">
            <h4>O pagamento foi recusado</h4>
            <p>Infelizmente, o pagamento não foi aprovado. Isso pode ocorrer devido a problemas com o seu cartão ou informações incorretas.</p>
            <p><strong>ID do Pedido:</strong> {{ $pedido->id }}</p>
            <p><strong>Motivo:</strong> {{ $motivo_recusa }}</p>
            <p>Por favor, verifique seus dados de pagamento e tente novamente.</p>
            <a href="{{ route('carrinho.finalizar') }}" class="btn btn-warning">Tentar novamente</a>
        </div>
    </div>
@endsection
