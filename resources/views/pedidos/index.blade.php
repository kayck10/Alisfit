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
    th,
    td {
        white-space: nowrap;
    }

    /* Ajustes de padding e margin em telas menores */
    @media (max-width: 576px) {

        .table td,
        .table th {
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

    .pagination a,
    .pagination .active {
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
                                <button type="button" class="btn btn-primary visualizarPedido" data-bs-toggle="modal"
                                    data-id="{{ $pedido->id }}" data-bs-target="#exampleModal">
                                    <i class="bi bi-eye-fill"></i>
                                </button>

                                </button>
                                <button class="btn btn-warning btn-sm" title="Editar" onclick="window.location.href=''">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" title="Excluir"
                                    onclick="if(confirm('Tem certeza que deseja excluir?')){ window.location.href='' }">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Modal Visualizar Pedido -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalPedidoLabel">Detalhes do Pedido</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="pedido-detalhes">
                                <p><strong>ID do Pedido:</strong> <span id="pedido-id">{{ $pedido->id }}</span></p>
                                <p><strong>Cliente:</strong> <span id="pedido-cliente">{{ $pedido->user->name }}</span></p>
                                <p><strong>Data:</strong> <span
                                        id="pedido-data">{{ $pedido->created_at->format('d/m/Y') }}</span></p>
                                <p><strong>Total:</strong> R$ <span
                                        id="pedido-total">{{ number_format($pedido->total, 2, ',', '.') }}</span></p>
                                <p><strong>Desconto:</strong> R$ <span
                                        id="pedido-desconto">{{ number_format($pedido->total - $pedido->total_com_desconto, 2, ',', '.') }}</span>
                                </p>
                                <p><strong>Status:</strong> <span
                                        id="pedido-status">{{ $pedido->status ? $pedido->status->desc : 'Desconhecido' }}</span>
                                </p>

                                <hr>

                                <form action="{{ route('pedidos.atualizar-status', $pedido->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="statusPedido" class="form-label">Alterar Status:</label>
                                        <select name="status" id="statusPedido" class="form-control">
                                            @foreach ($status as $s)
                                                <option value="{{ $s->id }}" {{ $pedido->status && $pedido->status->id == $s->id ? 'selected' : '' }}>
                                                    {{ $s->desc }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Atualizar Status</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Links de paginação -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="pagination-container">
                    {{ $pedidos->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-rbvoDNYtVfHyksoLPKvsE6E5STz3Uan08n3cJj2S/jfG10zvl29Hld97NQ91fmlk" crossorigin="anonymous">
</script>


<script>
 $('#formAtualizarStatus').on('submit', function(event) {
    event.preventDefault();

    var pedidoId = $('#pedidoIdInput').val();
    var statusId = $('#statusPedido').val();

    $.ajax({
        url: '/pedidos/' + pedidoId + '/status', // URL correta para atualizar o status
        method: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            status: statusId
        },
        success: function(response) {
            if (response.success) {
                // Atualiza o status na modal
                $('#pedido-status').text($('#statusPedido option:selected').text());
                // Fecha a modal
                $('#exampleModal').modal('hide');
                alert(response.message); // Exibe a mensagem de sucesso
            }
        },
        error: function(xhr, status, error) {
            alert('Ocorreu um erro ao atualizar o status.');
            console.log(xhr.responseText); // Adicione para debugar o erro
        }
    });
});

</script>
