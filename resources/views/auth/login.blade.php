<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar - AuraPark</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans flex items-center justify-center min-h-screen bg-[#070708] text-slate-200 p-6 flex-col">

    {{-- Flash Messages Floating --}}
    <div class="fixed top-6 right-6 w-full max-w-sm z-[100] flex flex-col items-end pointer-events-none space-y-2">
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

    {{-- Brilho de fundo --}}
    <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-[400px] h-[400px] bg-blue-600/5 blur-[100px] rounded-full pointer-events-none"></div>

    {{-- BRAND FORA DA CAIXA --}}
    <div class="mb-8 relative z-10">
        <h1 class="text-4xl font-black tracking-tighter text-white">Aura<span class="text-blue-500">Park</span></h1>
    </div>

    {{-- CARD --}}
    <div class="w-full max-w-[380px] rounded-[32px] p-8 md:p-10 bg-[#0b0b0d] border border-white/[0.05] shadow-2xl relative z-10">

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500 ml-1">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full px-5 py-3.5 rounded-xl bg-white/[0.02] border border-white/[0.06] text-white outline-none focus:border-blue-500/30 focus:bg-white/[0.04] transition-all duration-300 text-sm placeholder:text-slate-800"
                    placeholder="nome@aurapark.com">
            </div>

            <div class="space-y-2">
                <label class="block text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500 ml-1">Senha</label>
                <input type="password" name="password"
                    class="w-full px-5 py-3.5 rounded-xl bg-white/[0.02] border border-white/[0.06] text-white outline-none focus:border-blue-500/30 focus:bg-white/[0.04] transition-all duration-300 text-sm placeholder:text-slate-800"
                    placeholder="••••••••">
            </div>

            <div class="flex items-center justify-center pt-2">
                <a href="#" class="text-[9px] font-bold text-slate-700 hover:text-blue-400 transition-colors uppercase tracking-widest">
                    Esqueceu a senha?
                </a>
            </div>

            <div class="pt-2">
                <button type="submit"
                    class="w-full py-5 rounded-xl font-bold bg-blue-600 text-white shadow-lg shadow-blue-600/10 hover:bg-blue-500 hover:shadow-blue-500/20 transition-all duration-300 active:scale-[0.98] flex items-center justify-center group cursor-pointer border-none outline-none">
                    <span class="uppercase text-sm tracking-[0.25em]">Fazer Login</span>
                </button>
            </div>
        </form>
    </div>

</body>
</html>