@extends('layout.app')

@section('content')
    <style>
        .img-custom {
            width: auto;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }
    </style>

    <div class="container">
        <h2 class="mb-4">Editar Produto: {{ $produto->nome }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form style="margin-bottom: 200px;" action="{{ route('produtos.update', $produto->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" name="nome" id="nome" class="form-control"
                    value="{{ old('nome', $produto->nome) }}" required>
            </div>

            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição</label>
                <textarea name="descricao" id="descricao" class="form-control">{{ old('descricao', $produto->descricao) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="preco" class="form-label">Preço</label>
                <input type="number" name="preco" id="preco" class="form-control"
                    value="{{ old('preco', $produto->preco) }}" required>
            </div>

            <div class="mb-3">
                <label for="colecao_id" class="form-label">Coleção</label>
                <select name="colecao_id" id="colecao_id" class="form-control" required>
                    @foreach ($colecoes as $colecao)
                        <option value="{{ $colecao->id }}" {{ $produto->colecao_id == $colecao->id ? 'selected' : '' }}>
                            {{ $colecao->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="imagem" class="form-label">Imagem</label>
                <input type="file" name="imagem" id="imagem" class="form-control">
                @if ($produto->imagem)
                    <img style="height: 200px;" src="{{ asset('storage/' . $produto->imagem) }}" class="img-fluid mt-2"
                        alt="{{ $produto->nome }}">
                @endif
            </div>

            <div class="mb-3">
                <h4>Tamanhos e Quantidades</h4>
                <div id="tamanhos-list">
                    @foreach ($produto->tamanhos as $index => $tamanho)
                        <div class="row mb-2 tamanho-row" data-index="{{ $index }}">
                            <div class="col-md-5">
                                <label for="tamanhos_{{ $index }}" class="form-label">Tamanho</label>
                                <select name="informacoes[{{ $index }}][tamanhos]"
                                    id="tamanhos_{{ $index }}" class="form-control">
                                    @foreach ($tamanhos as $t)
                                        <option value="{{ $t->id }}"
                                            {{ $t->id == $tamanho->id ? 'selected' : '' }}>{{ $t->desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label for="quantidade_{{ $index }}" class="form-label">Quantidade</label>
                                <input type="number" name="informacoes[{{ $index }}][quantidades]"
                                    id="quantidade_{{ $index }}" class="form-control"
                                    value="{{ old('informacoes[' . $index . '][quantidades]', $tamanho->pivot->quantidade) }}"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-tamanho">Excluir</button>

                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-primary" id="add-tamanho">Adicionar Tamanho</button>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-success">Atualizar Produto</button>
            </div>
        </form>
    </div>

    <script>
        let tamanhoIndex = {{ count($produto->tamanhos) }};

        document.getElementById('add-tamanho').addEventListener('click', function() {
            const tamanhoList = document.getElementById('tamanhos-list');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-2', 'tamanho-row');
            newRow.setAttribute('data-index', tamanhoIndex);

            newRow.innerHTML = `
        <div class="col-md-5">
            <label for="tamanhos_${tamanhoIndex}" class="form-label">Tamanho</label>
            <select name="informacoes[${tamanhoIndex}][tamanhos]" id="tamanhos_${tamanhoIndex}" class="form-control">
                @foreach ($tamanhos as $t)
                    <option value="{{ $t->id }}">{{ $t->desc }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-5">
            <label for="quantidade_${tamanhoIndex}" class="form-label">Quantidade</label>
            <input type="number" name="informacoes[${tamanhoIndex}][quantidades]" id="quantidade_${tamanhoIndex}" class="form-control" required>
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger remove-tamanho">Excluir</button>
        </div>
    `;

            tamanhoList.appendChild(newRow);
            tamanhoIndex++;
            attachRemoveEvents();
        });

        function attachRemoveEvents() {
            const removeButtons = document.querySelectorAll('.remove-tamanho');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const row = button.closest('.tamanho-row');
                    if (row) {
                        row.remove();
                    }
                });
            });
        }

        attachRemoveEvents();
    </script>
@endsection
