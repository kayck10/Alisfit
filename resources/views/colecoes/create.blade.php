@extends('Layout.app')

@section('title', 'Criar Coleção')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow-lg mb-5">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Criar Nova Coleção</h4>
                </div>
                <div class="card-body mb-5">
                    <form action="{{ route('colecoes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nome -->
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome da Coleção</label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descrição -->
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="rascunho" selected>Rascunho</option>
                                <option value="publicado">Publicado</option>
                            </select>
                        </div>

                        <!-- Upload da Imagem -->
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem da Coleção</label>
                            <input type="file" class="form-control @error('imagem') is-invalid @enderror" id="imagem" name="imagem" accept="image/*" required onchange="previewImage(event)">
                            @error('imagem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pré-visualização da imagem -->
                        <div class="text-center mb-3">
                            <img id="preview" src="#" alt="Pré-visualização da imagem" class="img-thumbnail d-none" style="max-width: 200px;">
                        </div>

                        <!-- Botão de Envio -->
                        <button type="submit" class="btn btn-dark w-100">Criar Coleção</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script para Pré-visualização da Imagem -->
<script>
    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let preview = document.getElementById('preview');
            preview.src = reader.result;
            preview.classList.remove('d-none');
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection
