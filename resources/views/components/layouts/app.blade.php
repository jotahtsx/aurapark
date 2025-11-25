<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script>
        (function() {
            const saved = localStorage.getItem('theme') || 'light';
            document.documentElement.classList.toggle('dark', saved === 'dark');
            document.documentElement.setAttribute('data-theme', saved);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-base-100 relative">
    <script>
        function toggleTheme() {
            const current = localStorage.getItem('theme') || 'light';
            const next = current === 'light' ? 'dark' : 'light';

            localStorage.setItem('theme', next);
            document.documentElement.classList.toggle('dark', next === 'dark');
            document.documentElement.setAttribute('data-theme', next);

            const iconElement = document.getElementById('theme-icon');
            if (iconElement) {
                iconElement.textContent = next === 'light' ? '🌙' : '☀️';
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            const saved = localStorage.getItem('theme') || 'light';
            const iconElement = document.getElementById('theme-icon');
            if (iconElement) {
                iconElement.textContent = saved === 'light' ? '🌙' : '☀️';
            }
        });
    </script>

    <x-flash />

    <x-layouts.header />
    <x-layouts.sidebar />

    <main class="ml-64 pt-20 px-8">
        @yield('content')
    </main>

</body>

</html>
