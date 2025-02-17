    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-black flex items-center justify-center h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-96">
            <div class="flex justify-center mb-6">
                <img src="{{asset('images/ALis - nova logo-03.png')}}" alt="Logo da Loja" class="w-32">
            </div>
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-6">Acesse sua conta</h2>
            <form action="{{route('login.store')}}" method="POST">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">E-mail</label>
                    <input type="email" id="email" name="email" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="password">Senha</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-800">
                </div>
                <button type="submit"
                        class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">Entrar</button>
            </form>
            <p class="text-center text-gray-600 mt-4">Esqueceu a senha? <a href="#" class="text-black font-semibold">Recuperar</a></p>
        </div>
    </body>
    </html>
