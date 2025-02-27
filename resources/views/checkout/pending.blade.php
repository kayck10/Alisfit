@extends('Layout.principal')

@section('content')
    <div class="container py-5">
        <h2 class="text-center mb-5 mt-5">Pagamento Pendente</h2>

        <div class="alert alert-warning">
            <h4>Seu pagamento está pendente</h4>
            <p>O pagamento foi iniciado, mas ainda está aguardando a confirmação. Isso pode ocorrer por alguns motivos, como a análise do pagamento ou questões bancárias.</p>
            <p><strong>ID do Pedido:</strong> {{ $pedido->id }}</p>
            <p>Aguarde um momento, você receberá uma notificação assim que o pagamento for confirmado.</p>
            <p>Enquanto isso, você pode acompanhar o status do seu pagamento.</p>
            <a href="" class="btn btn-info">Ver status do pedido</a>
        </div>
    </div>
@endsection
