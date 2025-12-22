<header x-data="{ userMenu: false, ...themeController() }"
    class="header-main h-20 flex items-center justify-between px-4 lg:px-8 border-b border-base-300 bg-base-100/80 backdrop-blur-md fixed top-0 right-0 z-40 transition-all duration-300">

    <div class="flex items-center gap-4">
        <button @click="window.dispatchEvent(new CustomEvent('toggle-sidebar'))"
            class="lg:hidden p-2 rounded-xl bg-base-200 text-base-content hover:bg-base-300 transition-all cursor-pointer border border-base-300">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <div class="flex items-center gap-3 lg:gap-5">
        <button @click="toggleTheme"
            :class="theme === 'light' ? 'bg-slate-100 border-slate-200 text-slate-600' : 'bg-slate-800 border-slate-700 text-yellow-200'"
            class="relative w-11 h-11 flex items-center justify-center rounded-2xl border transition-all duration-300 cursor-pointer focus:outline-none">
            <div x-show="theme === 'light'" x-transition.opacity class="absolute">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-11.314l1.414 1.414m11.314 11.314l-1.414-1.414M12 8a4 4 0 100 8 4 4 0 000-8z" /></svg>
            </div>
            <div x-show="theme === 'dark'" x-transition.opacity class="absolute">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20.354 15.354A9 9 0 118.646 3.646a7 7 0 1011.708 11.708z" /></svg>
            </div>
        </button>

        <div class="relative">
            <button @click="userMenu = !userMenu" @click.away="userMenu = false"
                class="w-11 h-11 flex items-center justify-center rounded-2xl border border-base-300 bg-base-200 hover:bg-base-300 transition-all cursor-pointer overflow-hidden focus:outline-none">
                <span class="text-sm font-bold text-base-content uppercase">
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                </span>
            </button>
            <div x-show="userMenu" x-cloak x-transition class="absolute right-0 mt-3 w-60 bg-base-100 border border-base-300 rounded-2xl shadow-2xl z-50 overflow-hidden">
                <div class="px-5 py-4 bg-base-200/50 border-b border-base-300">
                    <p class="text-[10px] opacity-50 font-bold uppercase tracking-widest">Sessão</p>
                    <p class="text-sm font-bold truncate">{{ Auth::user()->name ?? 'Usuário' }}</p>
                </div>
                <div class="p-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm rounded-xl text-error hover:bg-error/10 transition-colors font-bold cursor-pointer">
                            Sair da conta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>