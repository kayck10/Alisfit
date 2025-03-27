@extends('Layout.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Criar Cupom</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('cupons.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="codigo" class="form-label">Código do cupom</label>
            <input type="text" name="codigo" id="codigo" class="form-control" required>
        </div>

        <div class="row">
            <!-- Tipo de Cupom -->
            <div class="col-md-6 mb-3">
                <label for="tipo" class="form-label">Tipo de desconto</label>
                <select name="tipo" id="tipo" class="form-select" required onchange="atualizarValorInput()">
                    <option value="percentual">Percentual (%)</option>
                    <option value="fixo">Valor Fixo (R$)</option>
                </select>
            </div>

            <!-- Valor do Desconto -->
            <div class="col-md-6 mb-3">
                <label for="valor" class="form-label">Valor do desconto</label>
                <input type="number" name="valor" id="valor" class="form-control" placeholder="Digite o desconto em %" required>
            </div>
        </div>

        <div class="mb-3">
            <label for="quantidade" class="form-label">Quantidade disponível</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="expiracao" class="form-label">Data de expiração</label>
            <input type="date" name="expiracao" id="expiracao" class="form-control">
        </div>

        <div class="mb-3">
            <label for="ativo" class="form-label">Cupom ativo?</label>
            <select name="ativo" id="ativo" class="form-select">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
<div class="d-grid gap-2">
    <button type="submit" class="btn btn-primary">Finalizar <i class="bi bi-check-all"></i></button>

</div>
    </form>
</div>

<script>
    function atualizarValorInput() {
        let tipo = document.getElementById("tipo").value;
        let inputValor = document.getElementById("valor");

        if (tipo === "percentual") {
            inputValor.placeholder = "Digite o desconto em %";
        } else {
            inputValor.placeholder = "Digite o desconto em R$";
        }
    }
</script>
@endsection
