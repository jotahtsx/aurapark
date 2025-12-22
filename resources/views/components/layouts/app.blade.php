<!DOCTYPE html>
<html lang="pt-BR" class="{{ old('') }}">

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
    class="antialiased">

    @include('components.layouts.header')

    @include('components.layouts.sidebar')

    <main class="main-content px-8">
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
</body>

</html>