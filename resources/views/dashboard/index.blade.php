@extends('Layout.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <!-- Gráfico de barras -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>Gráfico de Barras - Exemplo</h5>
                </div>
                <div class="card-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico de linhas -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>Gráfico de Linhas - Exemplo</h5>
                </div>
                <div class="card-body">
                    <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detalhes adicionais com animação -->
    <div class="row" style="margin-bottom: 150px;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5>Detalhes do Gráfico</h5>
                </div>
                <div class="card-body">
                    <p class="lead">Aqui você pode encontrar detalhes sobre os dados apresentados nos gráficos acima.</p>
                    <div class="details-container">
                        <div class="detail-item">
                            <h6>Total de Vendas</h6>
                            <p class="count">150</p>
                        </div>
                        <div class="detail-item">
                            <h6>Novos Clientes</h6>
                            <p class="count">200</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Vendas
    var barChart = new Chart(document.getElementById('barChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($meses) !!},
            datasets: [{
                label: 'Total de Vendas',
                data: {!! json_encode($valores) !!},
                backgroundColor: "rgba(54, 162, 235, 0.6)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1
            }]
        }
    });

    // Usuários
    var lineChart = new Chart(document.getElementById('lineChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: {!! json_encode($mesesUsuarios) !!},
            datasets: [{
                label: 'Usuários com id_tipo = 4',
                data: {!! json_encode($totalUsuarios) !!},
                borderColor: '#28a745',
                tension: 0.1,
                fill: false
            }]
        }
    });

    // Animação de contagem dos detalhes
    document.addEventListener('DOMContentLoaded', function () {
        const counters = document.querySelectorAll('.count');
        counters.forEach(counter => {
            let count = 0;
            const target = parseInt(counter.innerText);
            const speed = 100; // Velocidade da animação

            const updateCount = () => {
                const increment = target / speed;
                count += increment;
                if (count < target) {
                    counter.innerText = Math.ceil(count);
                    requestAnimationFrame(updateCount);
                } else {
                    counter.innerText = target;
                }
            };

            updateCount();
        });
    });
</script>
@endpush

@section('styles')
<style>
    .details-container {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .detail-item {
        text-align: center;
        transition: transform 0.3s ease-in-out;
    }

    .detail-item:hover {
        transform: scale(1.1);
        cursor: pointer;
    }

    .count {
        font-size: 2rem;
        font-weight: bold;
        color: #000;
    }
</style>
@endsection
