<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status do Pedido Atualizado</title>
</head>
<body>
    <h1>Olá, {{ $pedido->user->name }}!</h1>

    <p>O status do seu pedido foi atualizado para: <strong>{{ $statusDesc }}</strong></p>

    <p>Detalhes do Pedido:</p>
    <ul>
        <li><strong>Pedido ID:</strong> {{ $pedido->id }}</li>
        <li><strong>Total:</strong> R$ {{ number_format($pedido->total, 2, ',', '.') }}</li>
        <li><strong>Data:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</li>
    </ul>

    <p>Obrigado por escolher nossa loja!</p>

    <p>Atenciosamente,</p>
    <p><strong>Equipe da Loja</strong></p>
</body>
</html>
