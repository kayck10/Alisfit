@if($pedidos->isEmpty())
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 50px 0; text-align: center;">
        <img src="{{ asset('img/empty-orders.png') }}" alt="Sem pedidos" style="width: 180px; opacity: 0.6; margin-bottom: 15px;">
        <p style="font-size: 20px; color: #555; font-weight: 500;">Você ainda não fez nenhum pedido.</p>
    </div>
@else
    <div style="max-width: 800px; margin: auto; padding: 20px;">
        <h2 style="text-align: center; font-size: 28px; margin-bottom: 10px; color: #222; font-weight: bold;">Meus Pedidos</h2>
        <p style="text-align: center; color: #666; font-size: 16px; margin-bottom: 25px;">Aqui estão os seus pedidos recentes.</p>

        <div style="display: flex; flex-direction: column; gap: 15px;">
            @foreach($pedidos as $pedido)
                <div style="border: 1px solid #ddd; padding: 18px; border-radius: 10px; background: linear-gradient(135deg, #ffffff, #f8f8f8); box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08); transition: transform 0.2s ease-in-out;">
                    <p style="font-size: 18px; font-weight: bold; color: #333; margin-bottom: 5px;">Pedido #{{ $pedido->id }}</p>
                    <p style="margin: 5px 0;"><strong>Total:</strong> <span style="color: #28a745; font-weight: bold;">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span></p>
                    <p style="margin: 5px 0;">
                        <strong>Status:</strong>
                        <span style="font-weight: bold; padding: 4px 8px; border-radius: 5px; color: white; background-color:
                            {{ $pedido->status->desc == 'Entregue' ? '#28a745' : ($pedido->status->desc == 'Pendente' ? '#ff9800' : '#007bff') }}">
                            {{ $pedido->status->desc }}
                        </span>
                    </p>
                    <p style="margin: 5px 0;"><strong>Data:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
                </div>
            @endforeach
        </div>
    </div>
@endif
