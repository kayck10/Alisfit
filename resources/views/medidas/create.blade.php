@extends('Layout.app')

@section('content')
    <div class="container py-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-gradient-primary text-white">
                        <h3 class="mb-0 text-center">
                            <i class="fas fa-ruler-combined mr-2"></i>Adicionar Medidas ao Produto
                        </h3>
                    </div>

                    <div class="card-body p-5">
                        <form action="{{ route('medidas.store') }}" method="POST" class="needs-validation" novalidate>
                            @csrf

                            <!-- Seção de Seleção do Produto -->
                            <div class="mb-4">
                                <div class="form-floating">
                                    <select class="form-select @error('produto_id') is-invalid @enderror" id="produto_id"
                                        name="produto_id" required>
                                        <option value="">Selecione um produto</option>
                                        @foreach ($produtos as $produto)
                                            <option value="{{ $produto->id }}"
                                                {{ old('produto_id') == $produto->id ? 'selected' : '' }}>
                                                {{ $produto->nome }} @if ($produto->codigo)
                                                    ({{ $produto->codigo }})
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="produto_id" class="form-label">Produto</label>
                                    @error('produto_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Seção de Tamanho -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="form-floating">
                                        <input type="text" class="form-control @error('tamanho') is-invalid @enderror"
                                            id="tamanho" name="tamanho" value="{{ old('tamanho') }}" placeholder="Tamanho"
                                            required>
                                        <label for="tamanho">Tamanho (P, M, G, GG, etc.)</label>
                                        @error('tamanho')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Seção de Medidas -->
                            <div class="card mb-4 border-primary">
                                <div class="card-header bg-light-secondary text-primary">
                                    <h5 class="mb-0">
                                        <i class="fas fa-ruler-horizontal mr-2"></i>Medidas em Centímetros
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="number" step="0.1" class="form-control" id="torax"
                                                    name="torax" value="{{ old('torax') }}" placeholder="Tórax">
                                                <label for="torax">Tórax</label>
                                                <small class="text-muted">Circunferência do tórax</small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="number" step="0.1" class="form-control" id="cintura"
                                                    name="cintura" value="{{ old('cintura') }}" placeholder="Cintura">
                                                <label for="cintura">Cintura</label>
                                                <small class="text-muted">Circunferência da cintura</small>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="number" step="0.1" class="form-control" id="quadril"
                                                    name="quadril" value="{{ old('quadril') }}" placeholder="Quadril">
                                                <label for="quadril">Quadril</label>
                                                <small class="text-muted">Circunferência do quadril</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" step="0.1" class="form-control" id="comprimento"
                                                    name="comprimento" value="{{ old('comprimento') }}"
                                                    placeholder="Comprimento">
                                                <label for="comprimento">Comprimento</label>
                                                <small class="text-muted">Comprimento total</small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-floating">
                                                <input type="number" step="0.1" class="form-control" id="altura"
                                                    name="altura" value="{{ old('altura') }}" placeholder="Altura">
                                                <label for="ombro">Altura</label>
                                                <small class="text-muted">Altura</small>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <!-- Seção de Observações -->
                            <div class="mb-4">
                                <div class="form-floating">
                                    <textarea class="form-control" id="observacoes" name="observacoes" style="height: 100px" placeholder="Observações">{{ old('observacoes') }}</textarea>
                                    <label for="observacoes">Observações</label>
                                    <small class="text-muted">Informações adicionais sobre as medidas</small>
                                </div>
                            </div>

                            <!-- Botões de Ação -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save mr-2"></i>Salvar Medidas
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-light-primary {
            background-color: #e3f2fd;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #0d0e0f 0%, #606569 100%);
        }

        .card {
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .form-floating>label {
            color: #495057;
        }

        .form-control,
        .form-select {
            border-radius: 0.375rem;
        }

        .invalid-feedback {
            display: block;
        }
    </style>

    <script>
        // Validação do formulário
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
@endsection
