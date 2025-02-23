@extends('layout.app')

@section('content')
    <div class="container">
        <h2 class="mb-5">Criar Novo Produto</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div style="margin-bottom: 200px" class="card">
            <div class="card-body">
                <form style="margin-bottom: 100px;" action="{{ route('produtos.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nome:</label>
                        <input placeholder="Adicione um nome ao produto" type="text" name="nome" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Descrição:</label>
                        <textarea placeholder="Deixe a descrição do seu produto" name="descricao" class="form-control"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Preço:</label>
                                <input placeholder="Valor do seu produto" type="number" name="preco" class="form-control"
                                    required min="0" step="0.01">
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

                    <div class="mb-3">
                        <label class="form-label">Imagens:</label>
                        <input type="file" id="input-imagens" name="imagens[]" class="form-control" multiple required>
                        <small class="text-muted">Você pode selecionar várias imagens</small>
                        <div id="preview-imagens" class="mt-3"></div>
                    </div>



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
                    <input type="text" name="informacoes[${contador}][cor]" class="form-control me-2"
                        placeholder="Digite a cor" required>

                    <input type="number" name="informacoes[${contador}][quantidades]" class="form-control me-2"
                        placeholder="Quantidade" min="1" required>

                    <button type="button" class="btn btn-danger" onclick="removerTamanho(this)">Remover</button>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
        }

        function removerTamanho(btn) {
            btn.parentElement.remove();
        }

        document.querySelector("form").addEventListener("submit", function(event) {
            let mensagensErro = [];

            document.querySelectorAll(".tamanho-item").forEach((item) => {
                let tamanho = item.querySelector("select[name^='informacoes'][name$='[tamanhos]']");
                let cor = item.querySelector("input[name^='informacoes'][name$='[cor]']");
                let quantidade = item.querySelector("input[name^='informacoes'][name$='[quantidades]']");

                if (!tamanho.value) mensagensErro.push("Por favor, selecione um tamanho.");
                if (!cor.value.trim()) mensagensErro.push("Por favor, insira uma cor.");
                if (!quantidade.value || quantidade.value <= 0) mensagensErro.push("Por favor, informe uma quantidade válida.");
            });

            if (mensagensErro.length > 0) {
                alert(mensagensErro.join("\n"));
                event.preventDefault();
            }
        });

        document.getElementById('input-imagens').addEventListener('change', function(event) {
        let previewContainer = document.getElementById('preview-imagens');
        previewContainer.innerHTML = "";

        Array.from(event.target.files).forEach(file => {
            let reader = new FileReader();
            reader.onload = function(e) {
                let img = document.createElement("img");
                img.src = e.target.result;
                img.classList.add("img-thumbnail", "me-2");
                img.style.width = "100px";
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
    </script>
@endsection
