@extends('layout.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Lista de Cupons</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('cupons.create') }}" class="btn btn-success mb-3">Criar <i class="bi bi-plus"></i></a>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Tipo</th>
                <th>Valor</th>
                <th>Quantidade</th>
                <th>Expiração</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cupons as $cupom)
            <tr id="cupom-row-{{ $cupom->id }}">
                <td>{{ $cupom->codigo }}</td>
                <td>{{ $cupom->tipo == 'percentual' ? 'Percentual (%)' : 'Valor Fixo (R$)' }}</td>
                <td>{{ $cupom->tipo == 'percentual' ? $cupom->valor . '%' : 'R$' . number_format($cupom->valor, 2, ',', '.') }}</td>
                <td>{{ $cupom->quantidade }}</td>
                <td>{{ $cupom->expiracao ? $cupom->expiracao->format('d/m/Y') : 'Sem expiração' }}</td>
                <td>
                    <span class="badge bg-{{ $cupom->ativo ? 'success' : 'danger' }}">
                        {{ $cupom->ativo ? 'Ativo' : 'Inativo' }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-warning btn-sm" onclick="editarCupom({{ $cupom }})"><i class="bi bi-pencil-square"></i></button>
                    <button class="btn btn-danger btn-sm" onclick="deletarCupom({{ $cupom->id }})"><i class="bi bi-trash3"></i></button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $cupons->links() }} {{-- Paginação --}}
</div>

{{-- Modal de Edição --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Cupom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEditar">
                    @csrf
                    @method('PUT')

                    <input type="hidden" id="editId">

                    <div class="mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" id="editCodigo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <select id="editTipo" class="form-select">
                            <option value="percentual">Percentual (%)</option>
                            <option value="fixo">Valor Fixo (R$)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Valor</label>
                        <input type="number" id="editValor" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Quantidade</label>
                        <input type="number" id="editQuantidade" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Expiração</label>
                        <input type="date" id="editExpiracao" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select id="editAtivo" class="form-select">
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Preencher modal de edição
function editarCupom(cupom) {
    document.getElementById('editId').value = cupom.id;
    document.getElementById('editCodigo').value = cupom.codigo;
    document.getElementById('editTipo').value = cupom.tipo;
    document.getElementById('editValor').value = cupom.valor;
    document.getElementById('editQuantidade').value = cupom.quantidade;
    document.getElementById('editExpiracao').value = cupom.expiracao;
    document.getElementById('editAtivo').value = cupom.ativo;

    var modal = new bootstrap.Modal(document.getElementById('modalEditar'));
    modal.show();
}

// Enviar edição via AJAX
document.getElementById('formEditar').addEventListener('submit', function(event) {
    event.preventDefault();

    let id = document.getElementById('editId').value;
    let formData = {
        codigo: document.getElementById('editCodigo').value,
        tipo: document.getElementById('editTipo').value,
        valor: document.getElementById('editValor').value,
        quantidade: document.getElementById('editQuantidade').value,
        expiracao: document.getElementById('editExpiracao').value,
        ativo: document.getElementById('editAtivo').value,
        _token: '{{ csrf_token() }}',
        _method: 'PUT'
    };

    fetch(`/cupons/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(formData)
    }).then(response => response.json())
      .then(data => {
        Swal.fire({
            title: 'Sucesso!',
            text: 'Cupom atualizado com sucesso!',
            icon: 'success',
            timer: 1500,
            showConfirmButton: false
        });

        // Remove aria-hidden da modal ANTES de fechar
        document.querySelector('.modal-backdrop').remove();
        document.getElementById('modalEditar').classList.remove('show');
        document.getElementById('modalEditar').style.display = 'none';
        document.body.classList.remove('modal-open');

        // Pequeno delay para evitar erro
        setTimeout(() => {
            window.location.reload();
        }, 500);

    }).catch(error => {
        Swal.fire('Erro!', 'Ocorreu um problema ao atualizar.', 'error');
    });
});

// Excluir cupom com SweetAlert2
function deletarCupom(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta ação não pode ser desfeita!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/cupons/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`cupom-row-${id}`).remove();
                    Swal.fire('Excluído!', 'O cupom foi removido com sucesso.', 'success');
                } else {
                    Swal.fire('Erro!', 'Não foi possível excluir o cupom.', 'error');
                }
            });
        }
    });
}
</script>
@endsection
