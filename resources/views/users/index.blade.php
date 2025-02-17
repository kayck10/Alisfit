@extends('layout.app')

@section('content')

<style>
    .table-container {
        margin: 0 auto;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .table-header {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }

    .btn-edit, .btn-delete {
        font-size: 18px;
        padding: 5px 10px;
    }

    .btn-edit {
        background-color: #007bff;
        border: none;
        color: white;
    }

    .btn-edit:hover {
        background-color: #0056b3;
    }

    .btn-delete {
        background-color: #dc3545;
        border: none;
        color: white;
    }

    .btn-delete:hover {
        background-color: #c82333;
    }
</style>

<div class="container mt-5">
    <div class="table-container">
        <div class="table-header">Lista de Usuários</div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>E-mail</th>
                    <th>Tipo de Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->tiposUsuarios->desc }}</td>
                        <td>
                            <a href="{{ route('users.edit', $usuario->id) }}" class="btn btn-edit">
                                Editar <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('users.destroy', $usuario->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete" onclick="return confirm('Tem certeza que deseja excluir este usuário?')">
                                     Excluir <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
