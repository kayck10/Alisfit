@extends('Layout.principal')

<style>
    /* Sidebar fixa à esquerda */
    #sidebar {
    width: 250px;
    height: 100vh; /* Sidebar cobrindo toda a altura da tela */
    background-color: #fff;
    color: #000;
    position: fixed;
    top: 0;
    left: 0;
    border-right: 1px solid #ddd;
    padding-top: 20px;
}

    #sidebar h4 {
        text-align: left;
        font-size: 22px;
        font-weight: bold;
        padding-left: 20px;
    }

    #sidebar ul {
        list-style: none;
        padding: 0;
    }

    #sidebar ul li {
        padding: 12px 20px;
        border-bottom: 1px solid #ddd;
    }

    #sidebar ul li a {
        text-decoration: none;
        color: #000;
        font-size: 16px;
        display: block;
    }

    #sidebar ul li a:hover,
    #sidebar ul li.active a {
        color: #000;
        font-weight: bold;
        border-left: 4px solid #28a745;
        padding-left: 16px;
    }

    /* Botão sair */
    .logout-btn {
        display: block;
        width: 90%;
        margin: 20px auto;
        padding: 10px;
        text-align: center;
        background-color: #000;
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
    }

    .logout-btn:hover {
        background-color: #333;
    }

    /* Conteúdo principal */
    #content {
    margin-left: 260px; /* Ajuste para evitar sobreposição */
    padding: 40px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
}

    #content h2 {
        font-size: 26px;
        font-weight: bold;
    }

    #content p {
        font-size: 18px;
        color: #555;
    }
</style>

@section('content')
          <div style="margin-top: 200px; " id="sidebar">
            <h4>Minha Conta</h4>
        <ul id="menu">
            <li class="active" data-page="visao-geral">
                <a href="#">Visão Geral</a>
            </li>
            <li data-page="meus-pedidos">
                <a href="#">Meus Pedidos</a>
            </li>
            <li data-page="dados-pessoais">
                <a href="#">Dados Pessoais</a>
            </li>

        </ul>
        <a href="#" class="logout-btn">Sair</a>
    </div>

        <!-- Conteúdo principal -->
        <div style="margin-top: 200px; margin-bottom: 300px;" id="content">
            <h2>Bem-vindo(a) de volta!</h2>
            <p>Aqui você pode visualizar e gerenciar todos os seus pedidos, endereços e detalhes da conta.</p>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                function carregarConteudo(page) {
                    fetch(`/loja/conta/${page}`)
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById("content").innerHTML = html;
                        })
                        .catch(error => {
                            document.getElementById("content").innerHTML = "<p>Erro ao carregar o conteúdo.</p>";
                        });
                }

                carregarConteudo("visao-geral");

                document.querySelectorAll("#menu li").forEach(item => {
                    item.addEventListener("click", function () {
                        document.querySelector("#menu li.active")?.classList.remove("active");
                        this.classList.add("active");
                        const page = this.getAttribute("data-page");
                        carregarConteudo(page);
                    });
                });
            });
        </script>

@endsection
