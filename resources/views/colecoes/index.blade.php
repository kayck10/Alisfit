@extends('Layout.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-center">Minhas Coleções</h2>

        <!-- Botão para criar coleção -->
        <div class="text-center mb-4">
            <a href="{{ route('colecoes.create') }}" class="btn btn-success">Criar Coleção</a>
        </div>

        <div class="row">
            @foreach ($colecoes as $colecao)
                <div style="margin-bottom: 250px;" class="col-md-4 col-sm-6 mb-4">
                    <div class="card shadow-sm">
                        <img src="{{ \App\Helpers\ColecaoHelper::getImageUrl($colecao) }}" alt="{{ $colecao->nome }}"
                            class="img-fluid">
                        <div class="card-body">
                            <h5 class="card-title">{{ $colecao->nome }}</h5>
                            <p class="card-text">{{ $colecao->descricao ?? 'Sem descrição' }}</p>
                            <button class="btn btn-primary btn-sm ver-detalhes" data-id="{{ $colecao->id }}">
                                Ver Detalhes
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para exibir detalhes da coleção -->
    <div class="modal fade" id="detalhesModal" tabindex="-1" aria-labelledby="detalhesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalhesModalLabel">Detalhes da Coleção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="modal-imagem" src="" class="img-fluid mb-3"
                        style="max-height: 300px; object-fit: cover;">
                    <h4 id="modal-nome"></h4>
                    <p id="modal-descricao"></p>

                </div>
                <div class="modal-footer">
                    <!-- Botão Editar -->
                    <a id="modal-editar" class="btn btn-warning">Editar <i class="bi bi-pencil-square"></i></a>

                    <!-- Botão Excluir -->
                    <button id="modal-excluir" class="btn btn-danger">Excluir <i class="bi bi-trash-fill"></i></button>


                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".ver-detalhes").forEach(button => {
                button.addEventListener("click", function() {
                    let colecaoId = this.getAttribute("data-id");

                    fetch(`/colecao/show/${colecaoId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error("Erro ao carregar os detalhes.");
                            }
                            return response.json();
                        })
                        .then(data => {
                            document.getElementById("modal-nome").textContent = data.nome;
                            document.getElementById("modal-descricao").textContent = data
                                .descricao;
                            document.getElementById("modal-imagem").src = data.imagem_url;

                            document.getElementById("modal-editar").href =
                                `/colecao/edit/${colecaoId}`;
                            document.getElementById("modal-excluir").setAttribute("data-id",
                                colecaoId);

                            $("#detalhesModal").modal("show");
                        })
                        .catch(error => {
                            console.error("Erro ao carregar os detalhes:", error);
                            alert("Erro ao carregar os detalhes da coleção.");
                        });
                });
            });

            document.getElementById("modal-excluir").addEventListener("click", function() {
                let colecaoId = this.getAttribute("data-id");

                Swal.fire({
                    title: "Tem certeza?",
                    text: "Essa ação não pode ser desfeita!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sim, excluir!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/colecao/destroy/${colecaoId}`, {
                                method: "DELETE",
                                headers: {
                                    "X-CSRF-TOKEN": document.querySelector(
                                        'meta[name="csrf-token"]').getAttribute("content"),
                                    "Content-Type": "application/json"
                                }
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error("Erro ao excluir.");
                                }
                                return response.json();
                            })
                            .then(data => {
                                Swal.fire("Excluído!", "A coleção foi removida com sucesso.",
                                    "success");
                                $("#detalhesModal").modal("hide");
                                location.reload();
                            })
                            .catch(error => {
                                console.error("Erro ao excluir:", error);
                                Swal.fire("Erro!", "Não foi possível excluir.", "error");
                            });
                    }
                });
            });

        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
