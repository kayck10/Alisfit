@extends('Layout.app')

@section('content')

<style>
    .form-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 30px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    .form-header {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        font-weight: bold;
        border-bottom: 2px solid #000;
        padding-bottom: 10px;
    }

    .form-group label {
        font-weight: bold;
        font-size: 16px;
    }

    .form-control {
        height: 45px;
        font-size: 16px;
    }

    .btn-primary {
        background: #000;
        border: none;
        font-size: 18px;
        padding: 10px;
    }

    .btn-primary:hover {
        background: #333;
    }

    .alert {
        max-width: 800px;
        margin: 0 auto;
    }
</style>

<div class="container mt-5">
    <div class="form-container">
        <div class="form-header">Criar Usuário</div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="senha">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="senha_confirmation">Confirme a Senha</label>
                    <input type="password" class="form-control" id="senha_confirmation" name="senha_confirmation" required>
                </div>
            </div>

            <div class="form-group mb-4">
                <label for="id_tipos_usuarios">Tipo de Usuário</label>
                <select class="form-control" id="id_tipos_usuarios" name="id_tipos_usuarios" required>
                    <option value="">Selecione um tipo de usuário</option>
                    @foreach($tiposUsuarios as $tipo)
                        <option value="{{ $tipo->id }}">{{ $tipo->desc }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Criar Usuário</button>
        </form>
    </div>
</div>

@endsection
