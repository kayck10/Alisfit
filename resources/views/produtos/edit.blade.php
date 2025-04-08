@extends('Layout.app')

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
                <label class="form-label">Imagens</label>
                <input type="file" id="input-imagens" name="imagens[]" class="form-control" multiple
                    accept="image/jpeg,image/png,image/jpg,image/gif">
                <small class="text-muted">Formatos aceitos: JPEG, PNG, JPG, GIF. Tamanho máximo: 5MB por imagem.</small>
                <button type="button" id="adicionar-mais-imagens" class="btn btn-secondary mt-2">Adicionar mais
                    imagens</button>

                <!-- Preview das imagens existentes e novas -->
                <div id="preview-imagens-container" class="mt-3">
                    @foreach ($produto->imagens as $imagem)
                        <div class="card d-inline-block me-3 mb-3" style="width: 180px;" data-id="{{ $imagem->id }}">
                            <div class="card-body text-center">
                                @php
                                    $tempProduto = new stdClass();
                                    $tempProduto->imagens = collect([$imagem]);
                                @endphp
                                <img src="{{ \App\Helpers\ImageHelper::getProdutoImagemUrl($tempProduto) }}"
                                    class="img-fluid mb-2" style="max-height: 120px; object-fit: cover;">
                                <div class="small text-muted text-truncate">
                                    {{ basename($imagem->imagem) }}
                                </div>
                                <button type="button" class="btn btn-danger btn-sm w-100 remove-imagem-btn">
                                    <i class="fas fa-trash"></i> Remover
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Preview das novas imagens que serão adicionadas -->
                <div id="preview-novas-imagens" class="mt-3"></div>
            </div>


            <div class="mb-3 form-check form-switch">
                <input type="checkbox" name="destaque" id="destaque" class="form-check-input" value="1"
                    {{ $produto->destaque ? 'checked' : '' }}>
                <label class="form-check-label" for="destaque">Produto em Destaque</label>
                <small class="text-muted d-block">Marcar para exibir este produto na seção de destaques</small>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="lancamento" id="lancamento" class="form-check-input" value="1"
                            {{ $produto->lancamento ? 'checked' : '' }}>
                        <label class="form-check-label" for="lancamento">Produto Lançamento</label>
                        <small class="text-muted d-block">Marcar para destacar como novo lançamento</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" name="oferta" id="oferta" class="form-check-input" value="1"
                            {{ $produto->oferta ? 'checked' : '' }}>
                        <label class="form-check-label" for="oferta">Produto em Oferta</label>
                        <small class="text-muted d-block">Marcar para destacar como produto em promoção</small>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <h4>Tamanhos, Cores e Quantidades</h4>
                <div id="tamanhos-list">
                    @foreach ($produto->tamanhos as $index => $tamanho)
                        <div class="row mb-2 tamanho-row" data-index="{{ $index }}">
                            <div class="col-md-4">
                                <label for="tamanhos_{{ $index }}" class="form-label">Tamanho</label>
                                <select name="informacoes[{{ $index }}][tamanhos]"
                                    id="tamanhos_{{ $index }}" class="form-control">
                                    @foreach ($tamanhos as $t)
                                        <option value="{{ $t->id }}"
                                            {{ $t->id == $tamanho->id ? 'selected' : '' }}>{{ $t->desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="cor_{{ $index }}" class="form-label">Cor</label>
                                <input type="text" name="informacoes[{{ $index }}][cor]"
                                    id="cor_{{ $index }}" class="form-control"
                                    value="{{ $tamanho->pivot->cor }}" required>
                            </div>
                            <div class="col-md-3">
                                <label for="quantidade_{{ $index }}" class="form-label">Quantidade</label>
                                <input type="number" name="informacoes[{{ $index }}][quantidades]"
                                    id="quantidade_{{ $index }}" class="form-control"
                                    value="{{ $tamanho->pivot->quantidade }}" min="1" required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-tamanho">
                                    <i class="fas fa-trash">Remover</i>
                                </button>
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
        document.addEventListener('DOMContentLoaded', function() {
            const inputImagens = document.getElementById('input-imagens');
            const previewNovasImagens = document.getElementById('preview-novas-imagens');
            const btnAdicionarMais = document.getElementById('adicionar-mais-imagens');
            let novasImagens = [];

            // Configura o botão para adicionar mais imagens
            btnAdicionarMais.addEventListener('click', function() {
                inputImagens.click();
            });

            // Lida com a seleção de novas imagens
            inputImagens.addEventListener('change', function(event) {
                const files = Array.from(event.target.files);

                if (files.length > 0) {
                    files.forEach((file, index) => {
                        file.order = novasImagens.length; // Mantém a ordem sequencial
                    });
                    novasImagens = novasImagens.concat(files);
                    exibirPreviewNovasImagens();
                    atualizarInputImagens();
                }
            });

            // Exibe o preview das novas imagens
            function exibirPreviewNovasImagens() {
                previewNovasImagens.innerHTML = "";

                novasImagens.forEach((file, index) => {
                    if (file.size > 50480 * 1024) {
                        toastr.error(`A imagem ${file.name} excede o tamanho máximo de 5MB`, 'Erro');
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        toastr.error(`O arquivo ${file.name} não é uma imagem válida`, 'Erro');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const card = document.createElement("div");
                        card.classList.add("card", "d-inline-block", "me-3", "mb-3");
                        card.style.width = "180px";

                        const cardBody = document.createElement("div");
                        cardBody.classList.add("card-body", "text-center");

                        // Imagem
                        const img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("img-fluid", "mb-2");
                        img.style.maxHeight = "120px";
                        img.style.objectFit = "cover";

                        // Nome do arquivo
                        const fileName = document.createElement("div");
                        fileName.textContent = file.name.length > 15 ?
                            file.name.substring(0, 15) + '...' : file.name;
                        fileName.classList.add("small", "text-muted", "text-truncate");

                        // Botão de remoção
                        const removeBtn = document.createElement("button");
                        removeBtn.innerHTML = '<i class="fas fa-trash"></i> Remover';
                        removeBtn.classList.add("btn", "btn-danger", "btn-sm", "w-100");
                        removeBtn.onclick = function(e) {
                            e.preventDefault();
                            novasImagens.splice(index, 1);
                            exibirPreviewNovasImagens();
                            atualizarInputImagens();
                        };

                        cardBody.appendChild(img);
                        cardBody.appendChild(fileName);
                        cardBody.appendChild(removeBtn);
                        card.appendChild(cardBody);
                        previewNovasImagens.appendChild(card);
                    };
                    reader.readAsDataURL(file);
                });
            }

            function atualizarInputImagens() {
                const dataTransfer = new DataTransfer();
                novasImagens.forEach(file => dataTransfer.items.add(file));
                inputImagens.files = dataTransfer.files;
            }

            document.querySelectorAll('.remove-imagem-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const card = this.closest('.card');
                    const imagemId = card.getAttribute('data-id');

                    if (confirm("Tem certeza que deseja remover esta imagem?")) {
                        fetch(`/produtos/remover-imagem/${imagemId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content,
                                    'Accept': 'application/json'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    card.remove();
                                    toastr.success("Imagem removida com sucesso!");
                                } else {
                                    toastr.error("Erro ao remover a imagem.");
                                }
                            })
                            .catch(error => {
                                toastr.error("Ocorreu um erro ao tentar remover a imagem.");
                            });
                    }
                });
            });
        });

        let tamanhoIndex = {{ count($produto->tamanhos) }};

        document.getElementById('add-tamanho').addEventListener('click', function() {
            const tamanhoList = document.getElementById('tamanhos-list');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mb-2', 'tamanho-row');
            newRow.setAttribute('data-index', tamanhoIndex);

            newRow.innerHTML = `
        <div class="col-md-4">
            <label for="tamanhos_${tamanhoIndex}" class="form-label">Tamanho</label>
            <select name="informacoes[${tamanhoIndex}][tamanhos]" id="tamanhos_${tamanhoIndex}" class="form-control">
                @foreach ($tamanhos as $t)
                    <option value="{{ $t->id }}">{{ $t->desc }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label for="cor_${tamanhoIndex}" class="form-label">Cor</label>
            <input type="text" name="informacoes[${tamanhoIndex}][cor]" id="cor_${tamanhoIndex}" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label for="quantidade_${tamanhoIndex}" class="form-label">Quantidade</label>
            <input type="number" name="informacoes[${tamanhoIndex}][quantidades]" id="quantidade_${tamanhoIndex}" class="form-control" min="1" required>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-tamanho">
                <i class="fas fa-trash">Remover</i>
            </button>
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
