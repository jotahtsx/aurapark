@extends('components.layouts.app')

@section('title', 'Usuários — Admin')

@section('content')
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

<div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200/50">
                    <th class="text-xs uppercase tracking-wider font-semibold">Usuário</th>
                    <th class="text-xs uppercase tracking-wider font-semibold">Status</th>
                    <th class="text-xs uppercase tracking-wider font-semibold">Data de Cadastro</th>
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
                                    <img src="{{ $user->avatar }}" alt="{{ $user->name }}" />
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
                    <td class="text-sm text-base-content/70">
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
                    <td colspan="4" class="py-20 text-center">
                        <div class="flex flex-col items-center">
                            <i data-lucide="users" class="w-12 h-12 opacity-10 mb-2"></i>
                            <p class="text-base-content/50">Nenhum usuário encontrado no sistema.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div class="bg-base-200/30 px-6 py-4 border-t border-base-300 flex flex-col md:flex-row justify-between items-center gap-4">
        <span class="text-sm text-base-content/60">
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