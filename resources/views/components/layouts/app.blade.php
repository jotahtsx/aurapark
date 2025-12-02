<!DOCTYPE html>
<html lang="pt-BR" class="{{ old('') }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Painel') — AuraPark</title>

    {{-- Carrega seus assets via Vite (ajuste se não usar Vite) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Lucide (icons) --}}
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        /* Ajustes mínimos para o layout fixo */
        body {
            min-height: 100vh;
        }

        .sidebar {
            width: 16rem;
        }

        .header-main {
            height: 4.5rem;
        }

        .main-content {
            margin-left: 16rem;
            padding-top: 4.5rem;
            min-height: 100vh;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 40;
            }

            .main-content {
                margin-left: 0;
                padding-top: 4.5rem;
            }
        }
    </style>
</head>

<body class="font-sans bg-gray-50 text-gray-900">

    {{-- HEADER --}}
    <header
        class="header-main fixed top-0 left-0 right-0 bg-white dark:bg-slate-900 border-b border-gray-200 dark:border-slate-700 z-30 shadow-sm flex items-center">
        <div class="w-64 flex items-center justify-center h-full">
            <a href="{{ route('dashboard.home') }}"
                class="text-2xl font-bold tracking-wide text-gray-900 dark:text-white">AuraPark</a>
        </div>

        <div class="flex-1 flex items-center justify-end pr-6">
            {{-- Theme toggle --}}
            <button id="btn-theme"
                class="p-2 rounded-full bg-indigo-50 dark:bg-slate-700 hover:bg-indigo-100 dark:hover:bg-slate-600 transition mr-3"
                aria-label="Alternar tema">
                <span id="theme-icon" class="flex items-center"></span>
            </button>

            {{-- Logout form --}}
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit"
                    class="px-3 py-2 rounded-md text-sm font-medium bg-red-600 text-white hover:bg-red-700 transition">Sair</button>
            </form>
        </div>
    </header>

    {{-- SIDEBAR --}}
    <aside id="sidebar"
        class="sidebar fixed top-0 left-0 h-full bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 pt-16 z-20">
        <nav class="p-4 space-y-2">
            <a href="{{ route('dashboard.home') }}"
                class="flex items-center p-3 rounded-lg font-semibold bg-gray-100 dark:bg-slate-700 text-indigo-700 dark:text-indigo-300">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                Visão Geral
            </a>

            <a href="#"
                class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                Usuários
            </a>

            <a href="#"
                class="flex items-center p-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-slate-700 transition">
                <i data-lucide="calendar-check" class="w-5 h-5 mr-3"></i>
                Reservas
            </a>
        </nav>
    </aside>

    {{-- MAIN --}}
    <main class="main-content px-8">
        {{-- Espaço para flashes --}}
        <div class="max-w-3xl mx-auto">
            <div class="mb-6">
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
        </div>

        @yield('content')
    </main>

    <script>
        // Renderiza ícones Lucide após a injeção do HTML
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

        // THEME TOGGLE (salva no localStorage e aplica 'dark' na raiz)
        (function() {
            const html = document.documentElement;
            const btn = document.getElementById('btn-theme');
            const iconHolder = document.getElementById('theme-icon');

            function setIcon(isDark) {
                iconHolder.innerHTML = isDark ?
                    '<i data-lucide="sun" class="w-5 h-5"></i>' :
                    '<i data-lucide="moon" class="w-5 h-5"></i>';
                lucide.createIcons();
            }

            const saved = localStorage.getItem('theme');
            const isDark = saved === 'dark';
            if (isDark) html.classList.add('dark');
            setIcon(isDark);

            btn.addEventListener('click', function() {
                const darkNow = html.classList.toggle('dark');
                localStorage.setItem('theme', darkNow ? 'dark' : 'light');
                setIcon(darkNow);
            });
        })();
    </script>

</body>

</html>
