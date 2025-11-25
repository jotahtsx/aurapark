<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Entrar - AuraPark</title>

    @vite(['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'])
</head>

<body class="font-sans flex flex-col items-center justify-center min-h-screen relative bg-gray-100">

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

    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold tracking-wide text-gray-900">
            AuraPark
        </h1>
    </div>

    <div class="w-full max-w-sm rounded-xl shadow-lg p-8 bg-white text-gray-900">

        <form method="POST" action="{{ route('login') }}" class="w-full">
            @csrf

            <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email:</label>

            <input type="email" name="email" id="email" value="{{ old('email') }}"
                class="w-full mb-4 px-4 py-3 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 bg-gray-100 text-gray-900"
                placeholder="seuemail@exemplo.com" autofocus>
            <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Senha:</label>

            <input type="password" name="password" id="password"
                class="w-full mb-6 px-4 py-3 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 bg-gray-100 text-gray-900"
                placeholder="********">

            <div class="w-full mb-6 text-center">
                <a href="#" class="text-sm uppercase hover:underline text-blue-600">
                    Recuperar minha senha
                </a>
            </div>

            <button type="submit"
                class="liquid-fill relative overflow-hidden w-full py-4 mt-2 font-bold uppercase border-2 rounded-lg tracking-wider text-blue-600 border-blue-600 cursor-pointer transition duration-300 ease-in-out hover:shadow-lg">
                <span class="button-text relative z-10 transition-colors duration-500">
                    Fazer Login
                </span>
            </button>

            <p class="mt-6 text-xs text-center text-gray-500">
                Ao fazer login você declara que está ciente e concorda com os termos de uso da nossa empresa.
            </p>
        </form>
    </div>

    <div class="mt-6 text-center text-sm flex justify-center items-center gap-1 text-gray-600">
        Com <span class="text-red-500">♥</span>,
        <a href="#" class="hover:underline text-blue-600">
            jotahdev
        </a>
    </div>

</body>

</html>
