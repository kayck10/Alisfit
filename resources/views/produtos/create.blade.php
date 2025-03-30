@extends('Layout.app')

@section('content')
    <div style="margin-bottom: 100px;" class="container">
        <h2 class="mb-5">Criar Novo Produto</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <form action="{{ route('produtos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Campos do produto -->
                    <div class="mb-3">
                        <label class="form-label">Nome:</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição:</label>
                        <textarea name="descricao" class="form-control"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Preço:</label>
                                <input type="number" name="preco" class="form-control" required min="0" step="0.01">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Coleção:</label>
                                <select name="colecao_id" class="form-control" required>
                                    <option value="" selected disabled>Selecione a coleção</option>
                                    @foreach ($colecoes as $colecao)
                                        <option value="{{ $colecao->id }}">{{ $colecao->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gênero:</label>
                                <select name="genero_id" class="form-control" required>
                                    <option value="" selected disabled>Selecione o gênero</option>
                                    @foreach ($generos as $genero)
                                        <option value="{{ $genero->id }}">{{ $genero->desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tipo de peça:</label>
                                <select name="tipo_produto_id" class="form-control" required>
                                    <option value="" selected disabled>Selecione o tipo de peça</option>
                                    @foreach ($pecas as $peca)
                                        <option value="{{ $peca->id }}">{{ $peca->desc }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Campo de imagens -->
                    <div class="mb-3">
                        <label class="form-label">Imagens:</label>
                        <input type="file" id="input-imagens" name="imagens[]" class="form-control" multiple>
                        <small class="text-muted">Selecione várias imagens de uma vez ou adicione uma por vez</small>
                        <button type="button" id="adicionar-mais-imagens" class="btn btn-secondary mt-2">Adicionar mais imagens</button>
                        <div id="preview-imagens" class="mt-3"></div>
                    </div>

                    <!-- Campo de tamanhos e cores -->
                    <div class="mb-3">
                        <label class="form-label">Tamanhos e Cores:</label>
                        <div id="tamanhos-container"></div>
                        <button type="button" class="btn btn-primary mt-2" onclick="adicionarTamanho()">Adicionar Tamanho</button>
                    </div>

                    <button type="submit" class="btn btn-success">Salvar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // JavaScript para adicionar e remover tamanhos
        let contador = 0;

        function adicionarTamanho() {
            contador++;
            let container = document.getElementById('tamanhos-container');
            let html = `
                <div class="tamanho-item d-flex align-items-center mt-2">
                    <label class="me-2">Tamanho:</label>
                    <select name="informacoes[${contador}][tamanhos]" class="form-control me-2" required>
                        <option value="" selected disabled>Selecione o tamanho</option>
                        @foreach ($tamanhos as $tamanho)
                            <option value="{{ $tamanho->id }}">{{ $tamanho->desc }}</option>
                        @endforeach
                    </select>

                    <label class="me-2">Cor:</label>
                    <input type="text" name="informacoes[${contador}][cor]" class="form-control me-2" placeholder="Digite a cor" required>

                    <input type="number" name="informacoes[${contador}][quantidades]" class="form-control me-2" placeholder="Quantidade" min="1" required>

                    <button type="button" class="btn btn-danger" onclick="removerTamanho(this)">Remover</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function removerTamanho(btn) {
            btn.parentElement.remove();
        }

        document.addEventListener('DOMContentLoaded', function () {
    const inputImagens = document.getElementById('input-imagens');
    const previewContainer = document.getElementById('preview-imagens');
    const btnAdicionarMais = document.getElementById('adicionar-mais-imagens');
    let files = [];

    function exibirPreview() {
        previewContainer.innerHTML = "";
        files.forEach((file, index) => {
            let reader = new FileReader();
            reader.onload = function (e) {
                let img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("img-thumbnail", "me-2");
                img.style.width = "100px";

                let removeBtn = document.createElement("button");
                removeBtn.innerText = "Remover";
                removeBtn.classList.add("btn", "btn-danger", "btn-sm", "ms-2");
                removeBtn.onclick = function () {
                    files.splice(index, 1); // Remove a imagem do array
                    exibirPreview(); // Atualiza o preview
                    atualizarInputImagens(); // Atualiza o input de arquivos
                };

                let container = document.createElement("div");
                container.classList.add("d-inline-block", "mb-2");
                container.appendChild(img);
                container.appendChild(removeBtn);
                previewContainer.appendChild(container);
            };
            reader.readAsDataURL(file);
        });
    }

    function atualizarInputImagens() {
        const dataTransfer = new DataTransfer();
        files.forEach(file => dataTransfer.items.add(file));
        inputImagens.files = dataTransfer.files;
    }

    // Evento para adicionar imagens ao array
    inputImagens.addEventListener('change', function (event) {
        const newFiles = Array.from(event.target.files);
        files = files.concat(newFiles); // Adiciona as novas imagens ao array
        exibirPreview(); // Atualiza o preview
        atualizarInputImagens(); // Atualiza o input de arquivos
    });

    btnAdicionarMais.addEventListener('click', function () {
        inputImagens.click();
    });
});
    </script>
@endsection
