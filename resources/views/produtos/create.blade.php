@extends('Layout.app')

@section('content')
    <div style="margin-bottom: 100px;" class="container">
        <h2 class="mb-5">Criar Novo Produto</h2>

        <div class="card">
            <div class="card-body">
                <form id="produtoForm" action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Campos do produto -->
                    <div class="mb-3">
                        <label class="form-label">Nome: <span class="text-danger">*</span></label>
                        <input type="text" name="nome" class="form-control" required>
                        <div class="invalid-feedback">Por favor, insira o nome do produto.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição:</label>
                        <textarea name="descricao" class="form-control"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Preço: <span class="text-danger">*</span></label>
                                <input type="number" name="preco" class="form-control" required min="0"
                                    step="0.01">
                                <div class="invalid-feedback">Por favor, insira um preço válido (maior que 0).</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Coleção: <span class="text-danger">*</span></label>
                                <select name="colecao_id" class="form-control" required>
                                    <option value="" selected disabled>Selecione a coleção</option>
                                    @foreach ($colecoes as $colecao)
                                        <option value="{{ $colecao->id }}">{{ $colecao->nome }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Por favor, selecione uma coleção.</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gênero: <span class="text-danger">*</span></label>
                                <select name="genero_id" class="form-control" required>
                                    <option value="" selected disabled>Selecione o gênero</option>
                                    @foreach ($generos as $genero)
                                        <option value="{{ $genero->id }}">{{ $genero->desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Por favor, selecione um gênero.</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de peça: <span class="text-danger">*</span></label>
                                <select name="tipo_produto_id" class="form-control" required>
                                    <option value="" selected disabled>Selecione o tipo de peça</option>
                                    @foreach ($pecas as $peca)
                                        <option value="{{ $peca->id }}">{{ $peca->desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback">Por favor, selecione um tipo de peça.</div>
                            </div>
                        </div>
                    </div>
                    <!-- Campo de Destaque -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="destaque" id="destaque" class="form-check-input" value="1">
                        <label class="form-check-label" for="destaque">Produto em Destaque</label>
                        <small class="text-muted d-block">Marcar para exibir este produto na seção de destaques</small>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="lancamento" id="lancamento" class="form-check-input"
                                    value="1">
                                <label class="form-check-label" for="lancamento">Produto Lançamento</label>
                                <small class="text-muted d-block">Marcar para destacar como novo lançamento</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="oferta" id="oferta" class="form-check-input"
                                    value="1">
                                <label class="form-check-label" for="oferta">Produto em Oferta</label>
                                <small class="text-muted d-block">Marcar para destacar como produto em promoção</small>
                            </div>
                        </div>
                    </div>
                    <!-- Campo de imagens -->
                    <div class="mb-3">
                        <label class="form-label">Imagens: <span class="text-danger">*</span></label>
                        <input type="file" id="input-imagens" name="imagens[]" class="form-control" multiple required
                            accept="image/jpeg,image/png,image/jpg,image/gif">
                        <small class="text-muted">Formatos aceitos: JPEG, PNG, JPG, GIF. Tamanho máximo: 5MB por
                            imagem.</small>
                        <div class="invalid-feedback">Por favor, adicione pelo menos uma imagem válida.</div>
                        <button type="button" id="adicionar-mais-imagens" class="btn btn-secondary mt-2">Adicionar mais
                            imagens</button>
                        <div id="preview-imagens" class="mt-3"></div>
                        <div id="imagens-error" class="text-danger d-none">É necessário adicionar pelo menos uma imagem.
                        </div>
                    </div>

                    <!-- Campo de tamanhos e cores -->
                    <div class="mb-3">
                        <label class="form-label">Tamanhos e Cores: <span class="text-danger">*</span></label>
                        <div id="tamanhos-container"></div>
                        <div id="tamanhos-error" class="text-danger d-none">É necessário adicionar pelo menos um tamanho
                            com
                            cor e quantidade.</div>
                        <button type="button" class="btn btn-primary mt-2" onclick="adicionarTamanho()">Adicionar
                            Tamanho</button>
                    </div>

                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let contador = 0;
        let fileOrderCounter = 0;
        let files = [];

        function adicionarTamanho() {
            contador++;
            let container = document.getElementById('tamanhos-container');
            let html = `
    <div class="tamanho-item border p-3 mb-3">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Tamanho: <span class="text-danger">*</span></label>
                    <select name="informacoes[${contador}][tamanhos]" class="form-control" required>
                        <option value="" selected disabled>Selecione o tamanho</option>
                        @foreach ($tamanhos as $tamanho)
                            <option value="{{ $tamanho->id }}">{{ $tamanho->desc }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Selecione um tamanho</div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label class="form-label">Cor: <span class="text-danger">*</span></label>
                    <input type="text" name="informacoes[${contador}][cor]" class="form-control" placeholder="Digite a cor" required>
                    <div class="invalid-feedback">Informe a cor</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="mb-3">
                    <label class="form-label">Quantidade: <span class="text-danger">*</span></label>
                    <input type="number" name="informacoes[${contador}][quantidades]" class="form-control" placeholder="Quantidade" min="1" required>
                    <div class="invalid-feedback">Quantidade mínima: 1</div>
                </div>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger" onclick="removerTamanho(this)">
                    <i class="fas fa-trash"></i> Remover
                </button>
            </div>
        </div>
    </div>
    `;
            container.insertAdjacentHTML('beforeend', html);
            document.getElementById('tamanhos-error').classList.add('d-none');
        }

        function removerTamanho(btn) {
            btn.closest('.tamanho-item').remove();
            if (document.querySelectorAll('.tamanho-item').length === 0) {
                document.getElementById('tamanhos-error').classList.remove('d-none');
            }
        }

        // Preview de imagens
        document.addEventListener('DOMContentLoaded', function() {
            const inputImagens = document.getElementById('input-imagens');
            const previewContainer = document.getElementById('preview-imagens');
            const btnAdicionarMais = document.getElementById('adicionar-mais-imagens');

            // Objeto para armazenar promessas de carregamento
            const loadingPromises = [];

            function exibirPreview() {
                previewContainer.innerHTML = "";

                if (files.length === 0) {
                    previewContainer.innerHTML =
                    '<div class="alert alert-warning">Nenhuma imagem selecionada</div>';
                    document.getElementById('imagens-error').classList.remove('d-none');
                    return;
                }

                document.getElementById('imagens-error').classList.add('d-none');

                // Ordena as imagens pela ordem de seleção
                files.sort((a, b) => a.order - b.order).forEach((file, index) => {
                    // Verificação de tipo e tamanho
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        toastr.error(`O arquivo ${file.name} não é uma imagem válida`, 'Erro');
                        files.splice(index, 1);
                        return;
                    }

                    // Cria um container para a imagem que será preenchido quando carregar
                    const card = document.createElement("div");
                    card.classList.add("card", "d-inline-block", "me-3", "mb-3");
                    card.style.width = "180px";
                    card.dataset.index = index;

                    const cardBody = document.createElement("div");
                    cardBody.classList.add("card-body", "text-center");

                    // Placeholder enquanto a imagem carrega
                    const placeholder = document.createElement("div");
                    placeholder.innerHTML =
                        '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>';
                    cardBody.appendChild(placeholder);

                    // Processa a imagem em uma Promise
                    const promise = new Promise((resolve) => {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Remove o placeholder
                            cardBody.removeChild(placeholder);

                            // Cria a imagem
                            const img = document.createElement("img");
                            img.src = e.target.result;
                            img.classList.add("img-fluid", "mb-2");
                            img.style.maxHeight = "120px";
                            img.style.objectFit = "cover";
                            cardBody.prepend(img);

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
                                files.splice(index, 1);
                                // Reordena os índices
                                files.forEach((f, i) => f.order = i);
                                exibirPreview();
                                atualizarInputImagens();
                            };

                            cardBody.appendChild(fileName);
                            cardBody.appendChild(removeBtn);
                            resolve();
                        };
                        reader.readAsDataURL(file);
                    });

                    loadingPromises.push(promise);
                    card.appendChild(cardBody);
                    previewContainer.appendChild(card);
                });

                // Atualiza contador
                const counter = document.createElement('div');
                counter.className = 'small text-muted mt-2';
                counter.textContent = `${files.length}/15 imagens adicionadas`;
                previewContainer.appendChild(counter);
            }

            function atualizarInputImagens() {
                const dataTransfer = new DataTransfer();
                files.forEach(file => dataTransfer.items.add(file));
                inputImagens.files = dataTransfer.files;
            }

            inputImagens.addEventListener('change', function(event) {
                const newFiles = Array.from(event.target.files);
                if (newFiles.length > 0) {
                    // Verifica limite de 15 imagens
                    if (files.length + newFiles.length > 15) {
                        toastr.error('Limite máximo de 15 imagens atingido', 'Erro');
                        return;
                    }

                    // Adiciona a ordem sequencial
                    newFiles.forEach((file, index) => {
                        file.order = fileOrderCounter++;
                    });

                    files = files.concat(newFiles);
                    exibirPreview();
                    atualizarInputImagens();
                }
            });

            btnAdicionarMais.addEventListener('click', function() {
                inputImagens.click();
            });

            // Validação do formulário
            document.getElementById('produtoForm').addEventListener('submit', function(e) {
                let isValid = true;
                const requiredFields = this.querySelectorAll('[required]');

                // Valida campos obrigatórios
                requiredFields.forEach(field => {
                    if (!field.value) {
                        field.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                    }
                });

                // Valida imagens
                if (files.length === 0) {
                    inputImagens.classList.add('is-invalid');
                    document.getElementById('imagens-error').classList.remove('d-none');
                    isValid = false;
                } else {
                    inputImagens.classList.remove('is-invalid');
                    document.getElementById('imagens-error').classList.add('d-none');
                }

                // Valida tamanhos
                const tamanhosItems = document.querySelectorAll('.tamanho-item');
                if (tamanhosItems.length === 0) {
                    document.getElementById('tamanhos-error').classList.remove('d-none');
                    isValid = false;
                } else {
                    // Valida cada item de tamanho individualmente
                    tamanhosItems.forEach(item => {
                        const selects = item.querySelectorAll('select[required]');
                        const inputs = item.querySelectorAll('input[required]');

                        selects.forEach(select => {
                            if (!select.value) {
                                select.classList.add('is-invalid');
                                isValid = false;
                            }
                        });

                        inputs.forEach(input => {
                            if (!input.value) {
                                input.classList.add('is-invalid');
                                isValid = false;
                            }
                        });
                    });

                    if (isValid) {
                        document.getElementById('tamanhos-error').classList.add('d-none');
                    }
                }

                if (!isValid) {
                    e.preventDefault();
                    toastr.error('Por favor, preencha todos os campos obrigatórios corretamente.',
                        'Erro de Validação');

                    // Rolagem para o primeiro erro
                    const firstError = document.querySelector('.is-invalid');
                    if (firstError) {
                        firstError.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
                }
            });

            // Adiciona um tamanho inicial
            adicionarTamanho();
            // Exibe mensagem inicial para imagens
            exibirPreview();
        });
    </script>
@endsection
