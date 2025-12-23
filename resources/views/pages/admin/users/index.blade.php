@extends('components.layouts.app')

@section('title', 'Usuários — Admin')

@section('content')
{{-- Header da Página --}}
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-base-content">Gestão de Usuários</h1>
        <p class="text-base-content/60 text-sm">Administre os perfis e permissões do AuraPark.</p>
    </div>

    <button class="btn btn-primary shadow-lg shadow-primary/20">
        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
        Novo Usuário
    </button>
</div>

{{-- Filtros e Busca --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
    <form action="{{ route('admin.users.index') }}" method="GET" class="w-full max-w-md group">
        <div class="relative">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-base-content/30 group-focus-within:text-primary transition-all duration-300"></i>
            </div>

            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="digite o nome e aperte enter para buscar..."
                class="input w-full pl-12 pr-12 h-12 bg-base-200/50 border-none rounded-2xl 
           focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none
           shadow-sm transition-all duration-300 text-base-content 
           placeholder:text-base-content/30 placeholder:text-sm placeholder:font-light" />
            @if(request('search'))
            <a href="{{ route('admin.users.index') }}"
                class="absolute inset-y-0 right-3 flex items-center px-2 text-base-content/20 hover:text-error transition-all duration-200">
                <i data-lucide="circle-x" class="w-5 h-5"></i>
            </a>
            @endif
        </div>
    </form>

    <div class="hidden md:flex gap-4">
        <div class="flex flex-col items-end">
            <span class="text-[10px] font-bold uppercase opacity-40 tracking-widest">Registros</span>
            <span class="text-sm font-semibold text-base-content">{{ $users->total() }} usuários</span>
        </div>
    </div>
</div>

{{-- Card da Tabela --}}
<div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200/50">
                    <th class="text-xs uppercase tracking-wider font-semibold">Usuário</th>
                    <th class="text-xs uppercase tracking-wider font-semibold">Status</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center">Data de Cadastro</th>
                    <th class="text-right text-xs uppercase tracking-wider font-semibold pr-6">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base-300">
                @forelse($users as $user)
                <tr class="hover:bg-base-200/40 transition-colors group">
                    <td>
                        <div class="flex items-center gap-4">
                            <div class="avatar shadow-sm">
                                <div class="mask mask-squircle w-11 h-11 bg-base-300">
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF'" />
                                </div>
                            </div>
                            <div>
                                <div class="font-bold text-base-content">{{ $user->name }} {{ $user->last_name }}</div>
                                <div class="text-xs text-base-content/50">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->status === 'active')
                        <div class="badge badge-success badge-sm gap-1 py-3 px-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            Ativo
                        </div>
                        @else
                        <div class="badge badge-ghost badge-sm gap-1 py-3 px-3 opacity-60">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            Inativo
                        </div>
                        @endif
                    </td>
                    <td class="text-sm text-base-content/70 text-center">
                        {{ $user->created_at->translatedFormat('d M, Y') }}
                    </td>
                    <th class="text-right pr-4">
                        <div class="flex justify-end gap-1">
                            <button class="btn btn-ghost btn-sm btn-square hover:text-primary transition-colors" title="Editar">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <button class="btn btn-ghost btn-sm btn-square hover:text-error transition-colors" title="Excluir">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </th>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-20 text-center text-base-content/50">
                        <div class="flex flex-col items-center">
                            <i data-lucide="search-x" class="w-12 h-12 opacity-20 mb-3"></i>
                            <p class="text-lg font-medium">Nenhum resultado encontrado</p>
                            <p class="text-sm">Tente ajustar sua busca ou limpar os filtros.</p>
                            @if(request('search'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-link btn-sm mt-2 text-primary">Limpar busca</a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer / Paginação --}}
    @if($users->hasPages())
    <div class="bg-base-200/30 px-6 py-4 border-t border-base-300 flex flex-col md:flex-row justify-between items-center gap-4">
        <span class="text-sm text-base-content/60 text-center md:text-left">
            Mostrando <span class="font-bold text-base-content">{{ $users->firstItem() }}</span>
            até <span class="font-bold text-base-content">{{ $users->lastItem() }}</span>
            de <span class="font-bold text-base-content">{{ $users->total() }}</span> registros
        </span>

        <div class="join border border-base-300 shadow-sm">
            @if ($users->onFirstPage())
            <button class="join-item btn btn-sm btn-disabled opacity-50">«</button>
            @else
            <a href="{{ $users->previousPageUrl() }}" class="join-item btn btn-sm bg-base-100 text-base-content hover:bg-base-200">«</a>
            @endif

            <button class="join-item btn btn-sm btn-primary no-animation px-4">
                Página {{ $users->currentPage() }}
            </button>

            @if ($users->hasMorePages())
            <a href="{{ $users->nextPageUrl() }}" class="join-item btn btn-sm bg-base-100 text-base-content hover:bg-base-200">»</a>
            @else
            <button class="join-item btn btn-sm btn-disabled opacity-50">»</button>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection