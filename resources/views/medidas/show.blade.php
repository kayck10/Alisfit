@extends('Layout.app')

@section('content')
<div class="container py-4">
    <!-- Cabeçalho e navegação entre produtos -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>
                <i class="fas fa-ruler-combined me-2"></i>
                @isset($produto)
                    Medidas: {{ $produto->nome }}
                @else
                    Todas as Medidas Cadastradas
                @endisset
            </h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('medidas.create') }}">Adicionar Medidas</a></li>
                    <li class="breadcrumb-item active">Visualizar Medidas</li>
                </ol>
            </nav>
        </div>

        <div class="dropdown">
            <button class="btn btn-outline-primary dropdown-toggle" type="button" id="produtosDropdown"
                data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-filter me-2"></i>
                @isset($produto)
                    Produto: {{ $produto->nome }}
                @else
                    Filtrar por Produto
                @endisset
            </button>
            <ul class="dropdown-menu" aria-labelledby="produtosDropdown">
                <li><a class="dropdown-item @empty($produto) active @endempty" href="{{ route('medidas.show') }}">Todos os Produtos</a></li>
                <li><hr class="dropdown-divider"></li>
                @foreach ($produtos as $p)
                <li>
                    <a class="dropdown-item @isset($produto) @if($produto->id == $p->id) active @endif @endisset"
                        href="{{ route('medidas.show', $p->id) }}">
                        {{ $p->nome }} ({{ $p->medidas_count }} medidas)
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Mensagens de status -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Formulário de edição (inicialmente oculto) -->
    <div class="card mb-4" id="editFormContainer" style="display: none;">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Editar Medidas</h5>
        </div>
        <div class="card-body">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="medida_id" id="medida_id">

                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="edit_tamanho" class="form-label">Tamanho</label>
                        <input type="text" class="form-control" id="edit_tamanho" name="tamanho" required>
                    </div>
                    <div class="col-md-3">
                        <label for="edit_torax" class="form-label">Tórax (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="edit_torax" name="torax">
                    </div>
                    <div class="col-md-3">
                        <label for="edit_cintura" class="form-label">Cintura (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="edit_cintura" name="cintura">
                    </div>
                    <div class="col-md-3">
                        <label for="edit_quadril" class="form-label">Quadril (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="edit_quadril" name="quadril">
                    </div>
                    <div class="col-md-3">
                        <label for="edit_comprimento" class="form-label">Comprimento (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="edit_comprimento" name="comprimento">
                    </div>
                    <div class="col-md-3">
                        <label for="edit_altura" class="form-label">Altura (cm)</label>
                        <input type="number" step="0.1" class="form-control" id="edit_altura" name="altura">
                    </div>
                    <div class="col-md-6">
                        <label for="edit_observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="edit_observacoes" name="observacoes" rows="2"></textarea>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de medidas -->
    <div class="card shadow-sm">
        <div class="card-body">
            @if ($medidas->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-ruler fa-3x text-muted mb-3"></i>
                    <h5>Nenhuma medida cadastrada</h5>
                    <a href="{{ route('medidas.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-2"></i>Adicionar Medidas
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                @unless(isset($produto))
                                <th>Produto</th>
                                @endunless
                                <th>Tamanho</th>
                                <th>Tórax (cm)</th>
                                <th>Cintura (cm)</th>
                                <th>Quadril (cm)</th>
                                <th>Comprimento (cm)</th>
                                <th>Altura (cm)</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medidas as $medida)
                                <tr>
                                    @unless(isset($produto))
                                    <td>
                                        <a href="{{ route('medidas.show', $medida->produto_id) }}" class="text-decoration-none">
                                            {{ $medida->produto->nome }}
                                        </a>
                                    </td>
                                    @endunless
                                    <td>{{ $medida->tamanho }}</td>
                                    <td>{{ $medida->torax ?? '-' }}</td>
                                    <td>{{ $medida->cintura ?? '-' }}</td>
                                    <td>{{ $medida->quadril ?? '-' }}</td>
                                    <td>{{ $medida->comprimento ?? '-' }}</td>
                                    <td>{{ $medida->altura ?? '-' }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-warning me-1"
                                            onclick="showEditForm({{ $medida }})">
                                            <i class="bi bi-pencil-square"></i>
                                                                                </button>
                                        <form action="{{ route('medidas.destroy', $medida->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Tem certeza que deseja excluir esta medida?')">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function showEditForm(medida) {
        document.getElementById('medida_id').value = medida.id;
        document.getElementById('edit_tamanho').value = medida.tamanho;
        document.getElementById('edit_torax').value = medida.torax || '';
        document.getElementById('edit_cintura').value = medida.cintura || '';
        document.getElementById('edit_quadril').value = medida.quadril || '';
        document.getElementById('edit_comprimento').value = medida.comprimento || '';
        document.getElementById('edit_altura').value = medida.altura || '';
        document.getElementById('edit_observacoes').value = medida.observacoes || '';

        document.getElementById('editForm').action = `/medidas/${medida.id}/update`;

        document.getElementById('editFormContainer').style.display = 'block';

        document.getElementById('editFormContainer').scrollIntoView({
            behavior: 'smooth'
        });
    }

    function cancelEdit() {
        // Oculta o formulário
        document.getElementById('editFormContainer').style.display = 'none';
    }
</script>

<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .dropdown-item.active {
        background-color: #0d6efd;
        color: white;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
    .dropdown-item.active:hover {
        background-color: #0b5ed7;
    }
</style>
@endsection
