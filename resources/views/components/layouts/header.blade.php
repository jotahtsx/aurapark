<header x-data="{
    theme: localStorage.getItem('theme') || 'dark',
    toggleTheme() {
        this.theme = this.theme === 'dark' ? 'light' : 'dark';
        document.documentElement.setAttribute('data-theme', this.theme);
        localStorage.setItem('theme', this.theme);
    }
}" x-init="document.documentElement.setAttribute('data-theme', theme)"
    class="w-full h-16 flex items-center justify-between px-6 border-b border-base-300 bg-base-100 shadow-sm fixed top-0 left-0 z-40">

    <div class="flex items-center gap-3">
        <span class="text-xl font-bold tracking-wide">AuraPark</span>
    </div>

    <div class="flex items-center gap-6">

        <button @click="toggleTheme"
            class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-base-200 transition-colors cursor-pointer">
            <svg x-show="theme === 'light'" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364-6.364l-1.414 1.414M7.05 16.95l-1.414 1.414m0-11.314l1.414 1.414m11.314 11.314l-1.414-1.414M12 8a4 4 0 100 8 4 4 0 000-8z" />
            </svg>

            <svg x-show="theme === 'dark'" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20.354 15.354A9 9 0 118.646 3.646a7 7 0 1011.708 11.708z" />
            </svg>
        </button>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="ml-4 px-3 py-2 rounded-lg hover:bg-base-200 transition-colors cursor-pointer">
                Deslogar
            </button>
        </form>

        <div class="w-10 h-10 rounded-full bg-base-300 shadow overflow-hidden flex items-center justify-center">
            <img src="https://ui-avatars.com/api/?name=User&background=random&color=fff"
                class="w-full h-full object-cover" alt="Avatar">
        </div>
    </div>
</header>
