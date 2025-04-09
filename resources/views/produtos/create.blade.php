@extends('Layout.app')

<style>
    .img-slot {
        width: 100px;
        height: 100px;
        border: 2px dashed #ccc;
        position: relative;
        background-color: #f8f9fa;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .img-slot img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .img-slot .remove-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(0,0,0,0.6);
        color: white;
        border: none;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        font-size: 12px;
        line-height: 18px;
        text-align: center;
        cursor: pointer;
    }
</style>

@section('content')
    <div style="margin-bottom: 100px;" class="container">
        <h2 class="mb-5">Criar Novo Produto</h2>

        <div class="card">
            <div class="card-body">
                <form id="produtoForm" action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
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
                                <input type="number" name="preco" class="form-control" required min="0" step="0.01">
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

                    <div class="mb-3 form-check">
                        <input type="checkbox" name="destaque" id="destaque" class="form-check-input" value="1">
                        <label class="form-check-label" for="destaque">Produto em Destaque</label>
                        <small class="text-muted d-block">Marcar para exibir este produto na seção de destaques</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="lancamento" id="lancamento" class="form-check-input" value="1">
                                <label class="form-check-label" for="lancamento">Produto Lançamento</label>
                                <small class="text-muted d-block">Marcar para destacar como novo lançamento</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input type="checkbox" name="oferta" id="oferta" class="form-check-input" value="1">
                                <label class="form-check-label" for="oferta">Produto em Oferta</label>
                                <small class="text-muted d-block">Marcar para destacar como produto em promoção</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Imagens: <span class="text-danger">*</span></label>
                        <div id="grid-imagens" class="d-flex flex-wrap gap-2">
                            <!-- Slots de imagens adicionados via JS -->
                        </div>
                        <input type="file" id="input-hidden" name="imagens[]" class="d-none" accept="image/*" multiple>
                        <small class="text-muted d-block mt-2">Clique em um quadrado para adicionar imagem. Máximo 10 imagens.</small>
                        <div id="imagens-error" class="text-danger d-none mt-2">É necessário adicionar pelo menos uma imagem.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tamanhos e Cores: <span class="text-danger">*</span></label>
                        <div id="tamanhos-container"></div>
                        <div id="tamanhos-error" class="text-danger d-none">É necessário adicionar pelo menos um tamanho com cor e quantidade.</div>
                        <button type="button" class="btn btn-primary mt-2" onclick="adicionarTamanho()">Adicionar Tamanho</button>
                    </div>

                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let contador = 0;

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
                                <i class="fas fa-trash"></i>
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

        // ---------------- IMAGENS ------------------

        let imagens = [];

        function atualizarSlots() {
            const grid = document.getElementById('grid-imagens');
            grid.innerHTML = '';

            for (let i = 0; i < 10; i++) {
                const slot = document.createElement('div');
                slot.classList.add('img-slot');
                slot.dataset.index = i;

                if (imagens[i]) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(imagens[i]);

                    const removeBtn = document.createElement('button');
                    removeBtn.classList.add('remove-btn');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.onclick = function () {
                        imagens.splice(i, 1);
                        atualizarSlots();
                    };

                    slot.appendChild(img);
                    slot.appendChild(removeBtn);
                } else {
                    slot.innerHTML = '<span>+</span>';
                    slot.onclick = () => document.getElementById('input-hidden').click();
                }

                grid.appendChild(slot);
            }

            document.getElementById('imagens-error').classList.toggle('d-none', imagens.length > 0);

            // Atualiza o input-hidden com os arquivos do array imagens[]
            const dataTransfer = new DataTransfer();
            imagens.forEach(file => dataTransfer.items.add(file));
            document.getElementById('input-hidden').files = dataTransfer.files;
        }

        document.getElementById('input-hidden').addEventListener('change', function (e) {
            const newFiles = Array.from(e.target.files);
            const total = imagens.length + newFiles.length;

            if (total > 10) {
                alert('Você só pode adicionar no máximo 10 imagens.');
                return;
            }

            imagens = imagens.concat(newFiles);
            atualizarSlots();
        });

        document.addEventListener('DOMContentLoaded', atualizarSlots);
    </script>

@endsection
