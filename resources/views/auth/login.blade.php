<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Entrar - AuraPark</title>
    @vite(['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'])
</head>

<body class="font-sans flex items-center justify-center min-h-screen bg-gray-100 text-gray-900">

    <div class="absolute top-6 right-6 w-full max-w-sm z-50 flex flex-col gap-2">
        @foreach ($errors->all() as $error)
            <x-flash type="error" :message="$error" />
        @endforeach

        @if (session('success'))
            <x-flash type="success" :message="session('success')" />
        @endif

        @if (session('error'))
            <x-flash type="error" :message="session('error')" />
        @endif
    </div>

    <div class="w-full max-w-sm rounded-xl shadow-lg p-8 bg-white">
        <h1 class="text-4xl font-bold mb-6 text-center">AuraPark</h1>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <label class="block mb-1 text-sm font-medium">Email:</label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full mb-4 px-4 py-3 rounded-lg bg-gray-100 focus:outline-none focus:ring-2"
                placeholder="seuemail@exemplo.com">

            <label class="block mb-1 text-sm font-medium">Senha:</label>
            <input type="password" name="password"
                class="w-full mb-6 px-4 py-3 rounded-lg bg-gray-100 focus:outline-none focus:ring-2"
                placeholder="********">

            <div class="mb-6 text-center">
                <a href="#" class="text-sm text-blue-600 hover:underline uppercase">Recuperar minha senha</a>
            </div>

            <button type="submit"
                class="w-full py-3 rounded-lg font-bold border-2 border-blue-600 text-blue-600 hover:shadow-lg transition">Fazer
                Login</button>

            <p class="mt-6 text-xs text-center text-gray-500">Ao fazer login você concorda com os termos.</p>
        </form>
    </div>

</body>

</html>
