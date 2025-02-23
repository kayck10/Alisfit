<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Mercado Pago</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h1>Checkout do Mercado Pago</h1>
    <button id="pay-button">Pagar</button>

    <script>
        $(document).ready(function () {
            $('#pay-button').on('click', function () {
                $.get('/checkout', function (data) {
                    if (data.init_point) {
                        window.location.href = data.init_point;
                    } else {
                        alert('Erro ao iniciar o pagamento!');
                    }
                }).fail(function () {
                    alert('Erro ao conectar com o servidor!');
                });
            });
        });
    </script>

</body>
</html>
    