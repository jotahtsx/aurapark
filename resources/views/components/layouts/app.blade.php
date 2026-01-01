<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Painel') — AuraPark</title>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/mask@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body x-data="{ 
        theme: localStorage.getItem('theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            localStorage.setItem('theme', this.theme);
            document.documentElement.setAttribute('data-theme', this.theme);
        }
    }"
    x-init="document.documentElement.setAttribute('data-theme', theme); lucide.createIcons();"
    class="antialiased bg-base-200/50 min-h-screen">
    <div class="fixed top-6 right-6 w-full max-w-[350px] z-[9999] flex flex-col gap-3 pointer-events-none">
        @foreach ($errors->all() as $error)
        <x-layouts.flash type="error" :message="$error" />
        @endforeach

        @if (session('success'))
        <x-layouts.flash type="success" :message="session('success')" />
        @endif

        @if (session('error'))
        <x-layouts.flash type="error" :message="session('error')" />
        @endif
    </div>

    @include('components.layouts.header')
    @include('components.layouts.sidebar')


    <main class="transition-all duration-300 pt-20 lg:pl-64">
        <div class="p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
        document.addEventListener('livewire:navigated', () => {
            lucide.createIcons();
        });
    </script>
</body>

</html>