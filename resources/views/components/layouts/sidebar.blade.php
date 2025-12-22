<div x-data="{ open: false }" 
     @toggle-sidebar.window="open = !open"
     class="contents">
    
    <div x-show="open" x-cloak @click="open = false" 
         x-transition:opacity
         class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 lg:hidden">
    </div>

    <aside id="sidebar"
        :class="open ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed top-0 left-0 w-64 h-full bg-base-200 border-r border-base-300 transition-transform duration-300 ease-in-out z-50">
        
        <div class="h-20 flex items-center px-8 border-b border-base-300">
            <span class="text-3xl font-bold tracking-tighter text-base-content transition-colors duration-200">
                AuraPark
            </span>
        </div>

        <nav class="p-4 space-y-2 pt-6">
            <p class="text-[10px] font-black uppercase opacity-40 px-4 mb-4 tracking-widest text-base-content">Menu Principal</p>

            <a href="{{ route('dashboard.home') }}"
                class="flex items-center p-3 rounded-xl font-bold bg-primary text-primary-content shadow-md transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                Visão Geral
            </a>

            <a href="#"
                class="flex items-center p-3 rounded-xl text-base-content/60 hover:text-base-content hover:bg-base-300 transition-all font-medium">
                <i data-lucide="users" class="w-5 h-5 mr-3"></i>
                Usuários
            </a>
        </nav>
    </aside>
</div>