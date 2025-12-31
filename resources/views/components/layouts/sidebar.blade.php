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
                class="flex items-center p-3 rounded-xl font-bold transition-all
        {{ request()->routeIs('dashboard.home') 
            ? 'bg-primary text-primary-content shadow-md' 
            : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 mr-3"></i>
                Visão Geral
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
        {{ request()->routeIs('admin.users.*') 
            ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' 
            : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">

                <i data-lucide="users"
                    class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-primary-content' : 'group-hover:text-primary' }}">
                </i>

                <span class="font-semibold">Usuários</span>
            </a>

            <a href="{{ route('admin.pricings.index') }}"
                class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
    {{ request()->routeIs('admin.pricings.*') 
        ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' 
        : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">

                <i data-lucide="banknote"
                    class="w-5 h-5 {{ request()->routeIs('admin.pricings.*') ? 'text-primary-content' : 'group-hover:text-primary' }}">
                </i>

                <span class="font-semibold">Precificações</span>
            </a>
            <a href="{{ route('admin.payments.index') }}"
                class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200
        {{ request()->routeIs('admin.payments.*') 
            ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' 
            : 'text-base-content/70 hover:bg-base-200 hover:text-base-content' }}">
                <i data-lucide="credit-card"
                    class="w-5 h-5 {{ request()->routeIs('admin.payments.*') ? 'text-primary-content' : 'group-hover:text-primary' }}">
                </i>
                <span class="font-semibold">Pagamentos</span>
            </a>

            <a href="{{ route('admin.monthly_customers.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 group
          {{ request()->routeIs('admin.monthly_customers.*') 
             ? 'bg-primary text-primary-content shadow-lg shadow-primary/20' 
             : 'hover:bg-base-200 text-base-content/70 hover:text-base-content' }}">

                <div class="flex items-center justify-center">
                    <i data-lucide="user-check" class="w-5 h-5 {{ request()->routeIs('admin.monthly_customers.*') ? 'text-white' : 'group-hover:scale-110 transition-transform' }}"></i>
                </div>

                <span class="font-semibold">Mensalistas</span>
            </a>
        </nav>
    </aside>
</div>