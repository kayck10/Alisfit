<style>
    .user-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        max-width: 600px;
        /* Limita a largura máxima */
        margin: 20px auto;
        /* Centraliza na tela */
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin: 10px 0;
        padding: 8px;
        border-bottom: 1px solid #ddd;
        font-size: 16px;
    }

    .info-item strong {
        font-weight: bold;
        color: #333;
    }

    .info-item span {
        color: #555;
    }
</style>

<h2>Dados Pessoais</h2>
<p>Aqui estão seus dados cadastrados.</p>

<div class="user-info">
    <div class="info-item">
        <strong>Nome:</strong>
        <span>{{ $user->name }}</span>
    </div>
    <div class="info-item">
        <strong>E-mail:</strong>
        <span>{{ $user->email }}</span>
    </div>

    <div class="info-item">
        <strong>Criado em:</strong>
        <span>{{ $user->created_at->format('d/m/Y') }}</span>
    </div>
</div>
