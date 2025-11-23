<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login - AuraPark</title>
    @vite(['resources/css/app.css', 'resources/css/auth.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: Inter, sans-serif;
        }

        .liquid-fill {
            position: relative;
            z-index: 0;
        }

        .liquid-fill::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: theme('colors.blue.400');
            transition: transform 0.5s ease-in-out;
            transform: scaleX(0);
            /* Começa invisível */
            transform-origin: left;
            z-index: -1;
        }

        .liquid-fill:hover::before {
            transform: scaleX(1);
        }

        .liquid-fill:hover span {
            color: theme('colors.gray.900');
            /* Texto escuro quando preenchido */
        }
    </style>
</head>

<body class="bg-gray-900 flex flex-col items-center justify-center min-h-screen relative">
    @php
        $flashType = $errors->any() ? 'error' : null;
        $flashMessage = $errors->any() ? $errors->first() : null;

        if (!$flashMessage) {
            $flashType = session('success') ? 'success' : (session('error') ? 'error' : null);
            $flashMessage = session('success') ?? session('error');
        }
    @endphp

    @if ($flashType)
        <x-flash :type="$flashType" :message="$flashMessage" />
    @endif

    {{-- Título --}}
    <div class="mb-8 text-center">
        <h1 class="text-4xl font-bold text-white tracking-wide">AuraPark</h1>
    </div>

    <div class="w-full max-w-sm bg-gray-800 rounded-xl shadow-lg p-8 flex flex-col items-center">
        <form method="POST" action="{{ route('login') }}" class="w-full">
            @csrf
            <label class="block mb-2 text-sm font-medium text-gray-300 text-center" for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                class="w-full mb-4 px-4 py-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="seuemail@exemplo.com" autofocus>

            <label class="block mb-2 text-sm font-medium text-gray-300 text-center" for="password">Senha:</label>
            <input type="password" name="password" id="password"
                class="w-full mb-6 px-4 py-3 rounded-lg bg-gray-700 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="********">

            <div class="w-full mb-6 text-center">
                <a href="#" class="text-sm text-blue-400 uppercase hover:underline">Recuperar minha senha</a>
            </div>

            <button type="submit"
                class="relative w-full py-4 mt-2 font-bold text-blue-400 uppercase
                           border-2 border-blue-400 rounded-lg tracking-wider
                           overflow-hidden cursor-pointer transition-colors duration-500 ease-in-out liquid-fill">
                <span class="relative z-10 transition-colors duration-500 ease-in-out">Fazer Login</span>
            </button>

            <p class="mt-6 text-xs text-gray-400 text-center">
                Ao fazer login você declara que está ciente e concorda com os termos de uso da nossa empresa.
            </p>
        </form>
    </div>

    <div class="mt-6 text-center text-gray-400 text-sm flex justify-center items-center gap-1">
        Com <span class="text-red-500">♥</span>,
        <a href="#" class="text-blue-400 hover:underline">jotahdev</a>
    </div>

</body>

</html>
