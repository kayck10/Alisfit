@extends('layout.app')

@section('title', 'Pedidos')
<style>
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Hover na linha da tabela */
    tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease-in-out;
    }

    .btn {
        transition: transform 0.2s ease;
    }

    .btn:hover {
        transform: scale(1.1);
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.3rem 0.6rem;
    }

    /* Ajusta a tabela para não ter largura fixa, melhorando a responsividade */
    table {
        width: 100%;
    }

    /* Adiciona um espaço extra nos ícones */
    .btn i {
        font-size: 1.2rem;
    }

    /* Certifique-se de que as colunas têm o tamanho apropriado */
    th, td {
        white-space: nowrap;
    }

    /* Ajustes de padding e margin em telas menores */
    @media (max-width: 576px) {
        .table td, .table th {
            padding: 0.6rem;
        }

        .btn {
            font-size: 0.8rem;
        }

        .btn i {
            font-size: 1rem;
        }
    }

    /* Estilo do painel de paginação */
    .pagination {
        display: flex;
        justify-content: center;
        margin-top: 1rem;
    }

    .pagination li {
        list-style: none;
    }

    .pagination a, .pagination .active {
        border-radius: 50%;
        padding: 8px 12px;
        margin: 0 4px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .pagination a:hover {
        background-color: #0056b3;
    }

    .pagination .active {
        background-color: #0056b3;
        pointer-events: none;
    }

    /* Remover setas da paginação */
    .pagination .page-item:first-child,
    .pagination .page-item:last-child {
        display: none;
    }

    /* Alinha a paginação com os itens da tabela */
    .pagination-container {
        display: flex;
        justify-content: flex-start;
        align-items: center;
    }
</style>

@section('content')
<div class="container mt-4">
    <h3 class="text-center mb-4">Lista de Pedidos</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped table-sm shadow-sm" id="tabelaPedidos">
            <thead class="table-dark">
                <tr>
                    <th>#ID</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Desconto</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pedidos as $pedido)
                    <tr class="fade-in">
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->user->name }}</td>
                        <td>{{ $pedido->created_at->format('Y-m-d') }}</td>
                        <td> <span class="text-dark">
                            @if ($pedido->status)
                                {{ $pedido->status->desc }}
                            @else
                                Desconhecido
                            @endif
                        </span></td>

                         <td>R$ {{ number_format($pedido->total, 2, ',', '.') }}</td>
                        <td>R$ {{ number_format($pedido->getTotalComDescontoAttribute(), 2, ',', '.') }}</td>
                        <td>
                            <button class="btn btn-info btn-sm" title="Visualizar" onclick="window.location.href=''"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-warning btn-sm" title="Editar" onclick="window.location.href=''"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" title="Excluir" onclick="if(confirm('Tem certeza que deseja excluir?')){ window.location.href='"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Links de paginação -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="pagination-container">
                {{ $pedidos->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
    <script>
        // Suavizar a transição ao carregar a página
        document.querySelectorAll('.fade-in').forEach((element, index) => {
            setTimeout(() => {
                element.classList.add('fade-in');
            }, index * 300);
        });
    </script>
@endpush
