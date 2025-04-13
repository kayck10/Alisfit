@extends('Layout.app')

@section('content')
    <div class="container mt-4">
        <h2>Tipos de Peças</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('tipos-produtos.create') }}" class="btn btn-success mb-3">Cadastrar Novo Tipo</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tipos as $tipo)
                    <tr>
                        <td>{{ $tipo->id }}</td>
                        <td>{{ $tipo->desc }}</td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $tipo->id }}"
                                data-desc="{{ $tipo->desc }}" data-bs-toggle="modal" data-bs-target="#editModal">
                                Editar
                            </button>


                            <form action="{{ route('tipos-produtos.destroy', $tipo->id) }}" method="POST"
                                style="display:inline-block" onsubmit="return confirm('Tem certeza que deseja excluir?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Modal de Edição -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" id="formEdit" class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Editar Tipo de Peça</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <input type="hidden" id="editBaseUrl" value="{{ url('/tipos/produtos') }}">

                    <div class="modal-body">
                        <input type="hidden" id="editId">
                        <div class="mb-3">
                            <label for="editDesc" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="editDesc" name="desc" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function () {
                    const id = this.dataset.id;
                    const desc = this.dataset.desc;

                    document.getElementById('editDesc').value = desc;

                    const baseUrl = document.getElementById('editBaseUrl').value;
                    const form = document.getElementById('formEdit');

                    form.setAttribute('action', `${baseUrl}/${id}`);
                });
            });
        });
    </script>

@endsection
