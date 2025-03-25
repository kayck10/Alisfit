@extends('Layout.principal')

@section('content')
    <div style="margin-top: 200px;" class="container py-5">
        <h2 class="text-center mb-5 fw-bold text-dark">Checkout</h2>

        <div class="row justify-content-center">
            <div class="col-lg-6">
                <!-- Card do Pedido -->
                <div class="card shadow-lg rounded">
                    <div class="card-header bg-info text-white text-center">
                        <h5 class="mb-0">Pedido #{{ $pedido->id }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <h4 class="fw-bold text-muted">Total: R$ {{ number_format($pedido->total, 2, ',', '.') }}</h4>

                        <!-- Formulário de Pagamento -->
                        <form action="{{ route('checkout.processar') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="pedidoId" value="{{ $pedido->id }}">
                            <input type="hidden" name="valor_total" value="{{ $pedido->total }}">
                            <input type="hidden" name="codigoCupom" value="{{ session('cupom.codigo') }}">

                            <button type="submit" class="btn btn-primary w-100 btn-lg fw-bold">
                                 Realizar pagamento para concluir seu pedido <i class="fas fa-credit-card"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
