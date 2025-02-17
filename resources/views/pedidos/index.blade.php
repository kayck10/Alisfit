@extends('layout.app')

@section('title', 'Pedidos')

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
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fade-in">
                        <td>001</td>
                        <td>João Silva</td>
                        <td>2025-01-20</td>
                        <td><span class="badge bg-success">Em andamento</span></td>
                        <td>
                            <button class="btn btn-info btn-sm" title="Visualizar"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" title="Excluir"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <tr class="fade-in">
                        <td>002</td>
                        <td>Ana Souza</td>
                        <td>2025-01-18</td>
                        <td><span class="badge bg-danger">Cancelado</span></td>
                        <td>
                            <button class="btn btn-info btn-sm" title="Visualizar"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" title="Excluir"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <tr class="fade-in">
                        <td>003</td>
                        <td>Carlos Pereira</td>
                        <td>2025-01-22</td>
                        <td><span class="badge bg-primary">Concluído</span></td>
                        <td>
                            <button class="btn btn-info btn-sm" title="Visualizar"><i class="bi bi-eye"></i></button>
                            <button class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-danger btn-sm" title="Excluir"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <!-- Mais linhas aqui -->
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('styles')
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
    </style>
@endpush

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
