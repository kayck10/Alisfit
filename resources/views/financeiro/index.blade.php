@extends('Layout.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Controle Financeiro</h2>

    <canvas style="margin-bottom: 250px;" id="financeiroChart"></canvas>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var ctx = document.getElementById("financeiroChart").getContext("2d");

        var chart = new Chart(ctx, {
            type: "bar",
            data: {
                labels: {!! json_encode($meses) !!},
                datasets: [{
                    label: "Total de Vendas por Mês",
                    data: {!! json_encode($valores) !!},
                    backgroundColor: "rgba(54, 162, 235, 0.6)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection
