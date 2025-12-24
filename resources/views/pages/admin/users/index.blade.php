@extends('components.layouts.app')

@section('title', 'Usuários — Gestão de Usuários')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-base-content">Gestão de Usuários</h1>
        <p class="text-base-content/60 text-sm">Visualize e gerencie todos os usuários cadastrados.</p>
    </div>

    <a href="#" onclick="event.preventDefault(); new_user_modal.showModal()" class="btn btn-primary shadow-lg shadow-primary/20">
        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
        Novo Usuário
    </a>
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
                                    @php
                                    $avatarUrl = str_starts_with($user->avatar ?? '', 'http')
                                    ? $user->avatar
                                    : ($user->avatar ? asset('storage/' . $user->avatar) : '');
                                    @endphp

                                    <img src="{{ $avatarUrl }}"
                                        alt="{{ $user->name }}"
                                        onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF'"
                                        class="object-cover w-full h-full" />
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
                            {{-- BOTÃO EDITAR: White no Dark / Azul no Light --}}
                            <a href="javascript:void(0)"
                                @click="$dispatch('edit-user', { 
                    id: {{ $user->id }}, 
                    name: '{{ $user->name }}', 
                    last_name: '{{ $user->last_name }}', 
                    email: '{{ $user->email }}', 
                    status: '{{ $user->status }}',
                    avatar: '{{ $user->avatar }}'
                })"
                                class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group
                       bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white
                       dark:bg-white/5 dark:text-white dark:hover:bg-white dark:hover:text-[#070708]"
                                title="Editar Usuário">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            </a>

                            {{-- LÓGICA DE DELETAR: Bloqueia se for o próprio usuário --}}
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('Atenção: Esta ação não pode ser desfeita. Confirmar exclusão?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group border-none outline-none
                               bg-red-600/10 text-red-600 hover:bg-red-600 hover:text-white
                               dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white"
                                    title="Excluir Usuário">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </form>
                            @else
                            {{-- ÍCONE DE PROTEÇÃO: Aparece no lugar do delete para você mesmo --}}
                            <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-base-300/50 text-base-content/30 cursor-help"
                                title="Proteção de conta: Você não pode se excluir.">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            @endif
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
<dialog id="new_user_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col max-h-[90vh] shadow-2xl border border-base-content/5">

        <div class="px-8 py-6 border-b border-base-200 bg-base-100 shrink-0">
            <h3 class="text-xl font-bold tracking-tighter text-base-content">Cadastrar Usuário</h3>
            <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Habilite um novo acesso ao sistema</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data"
            x-data="{ photoPreview: null }" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="flex-1 overflow-y-auto p-8 pt-6 space-y-8 custom-scrollbar scroll-smooth">

                <div class="flex flex-col items-center group">
                    <label class="relative cursor-pointer hover:scale-105 active:scale-95 transition-all">
                        <div class="w-24 h-24 rounded-[32px] bg-base-200/50 flex items-center justify-center border-2 border-dashed border-base-content/10 overflow-hidden shadow-inner">
                            <div x-show="!photoPreview" class="flex flex-col items-center opacity-30">
                                <i data-lucide="camera" class="w-6 h-6 mb-1 text-primary"></i>
                                <span class="text-[8px] font-black uppercase">Foto</span>
                            </div>
                            <template x-if="photoPreview">
                                <img :src="photoPreview" class="w-full h-full object-cover">
                            </template>
                        </div>
                        <input type="file" name="avatar" class="hidden" @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                        <div class="absolute -bottom-1 -right-1 bg-primary p-1.5 rounded-xl text-white shadow-lg shadow-primary/30">
                            <i data-lucide="plus" class="w-3 h-3"></i>
                        </div>
                    </label>
                    <p class="text-[10px] text-base-content/20 font-bold uppercase mt-3 tracking-widest">Avatar do Perfil</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Nome</span>
                        </label>
                        <input type="text" name="name" placeholder="Ex: Rodrigo" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Sobrenome</span>
                        </label>
                        <input type="text" name="last_name" placeholder="Ex: Oliveira" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                    </div>

                    <div class="form-control md:col-span-2 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">E-mail Corporativo</span>
                        </label>
                        <input type="email" name="email" placeholder="nome@aurapark.com" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Senha Inicial</span>
                        </label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Status da Conta</span>
                        </label>
                        <select name="status" class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-xs font-bold uppercase px-5">
                            <option value="active">🟢 Ativo</option>
                            <option value="inactive">🔴 Inativo</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="p-6 bg-base-100 border-t border-base-200/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" @click="new_user_modal.close()"
                    class="px-6 py-3 font-bold text-[10px] uppercase tracking-widest opacity-30 hover:opacity-100 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-10 py-4 rounded-2xl font-black bg-primary text-primary-content shadow-xl shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95 uppercase text-[10px] tracking-[0.2em]">
                    Salvar Registro
                </button>
            </div>
        </form>
    </div>

    {{-- Backdrop --}}
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10">
        <button>close</button>
    </form>
</dialog>


<dialog id="edit_user_modal" class="modal modal-bottom sm:modal-middle" x-data="{ 
    editPhotoPreview: null,
    userName: '',
    userEmail: '',
    userLastName: '',
    userStatus: 'active',
    userId: null
}"
    @edit-user.window="
    userId = $event.detail.id;
    userName = $event.detail.name;
    userLastName = $event.detail.last_name;
    userEmail = $event.detail.email;
    userStatus = $event.detail.status;
    
    /* Lógica Inteligente para Foto: Aceita URL completa (Dummy) ou caminho local */
    let avatar = $event.detail.avatar;
    if (avatar) {
        editPhotoPreview = (avatar.startsWith('http')) 
            ? avatar 
            : '/storage/' + avatar;
    } else {
        editPhotoPreview = null;
    }
    
    $el.showModal();
">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col max-h-[90vh] shadow-2xl border border-base-content/5 z-10">
        <div class="px-8 py-6 border-b border-base-200 bg-base-100 shrink-0 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold tracking-tighter text-base-content">Editar Perfil</h3>
                <p class="text-[10px] text-base-content/40 font-bold tracking-widest mt-1">Atualize as credenciais do operador</p>
            </div>
            <button @click="edit_user_modal.close()" class="btn btn-sm btn-circle btn-ghost opacity-30 border-none outline-none">✕</button>
        </div>

        <form :action="'/admin/users/' + userId" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="flex-1 overflow-y-auto p-8 pt-6 space-y-8 custom-scrollbar scroll-smooth">
                <div class="flex flex-col items-center group">
                    <label class="relative cursor-pointer hover:scale-105 transition-all">
                        <div class="w-24 h-24 rounded-[32px] bg-base-200 flex items-center justify-center border-2 border-dashed border-base-content/10 overflow-hidden shadow-inner">
                            <template x-if="!editPhotoPreview">
                                <span class="text-2xl font-black text-base-content/20" x-text="userName.charAt(0)"></span>
                            </template>
                            <template x-if="editPhotoPreview">
                                <img :src="editPhotoPreview" class="w-full h-full object-cover">
                            </template>
                        </div>
                        <input type="file" name="avatar" class="hidden" @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { editPhotoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                    </label>
                    <p class="text-[10px] text-primary font-bold uppercase mt-3 tracking-widest">Alterar Foto</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-bold text-[10px] uppercase opacity-40 tracking-widest">Nome</span></label>
                        <input type="text" name="name" x-model="userName" class="input bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/5 transition-all text-sm" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-bold text-[10px] uppercase opacity-40 tracking-widest">Sobrenome</span></label>
                        <input type="text" name="last_name" x-model="userLastName" class="input bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/5 transition-all text-sm" />
                    </div>
                    <div class="form-control md:col-span-2 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-bold text-[10px] uppercase opacity-40 group-focus-within:text-primary transition-all tracking-widest">E-mail de Acesso</span>
                        </label>
                        <input type="email" name="email" x-model="userEmail" required
                            placeholder="exemplo@email.com"
                            class="input w-full bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm placeholder:text-base-content/20" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-bold text-[10px] uppercase text-primary tracking-widest">Alterar Senha</span>
                        </label>
                        <input type="password" name="password" placeholder="Em branco para manter"
                            class="input w-full bg-primary/5 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm placeholder:text-primary/30" />
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-bold text-[10px] uppercase opacity-40 tracking-widest">Status do Operador</span>
                        </label>
                        <select name="status" x-model="userStatus"
                            class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-xs font-bold uppercase px-5 cursor-pointer">
                            <option value="active">🟢 Ativo</option>
                            <option value="inactive">🔴 Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-base-100 border-t border-base-200 flex items-center justify-end gap-3 shrink-0">
                <button type="button" @click="edit_user_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest border-none">Cancelar</button>
                <button type="submit" class="px-10 py-4 rounded-2xl font-black bg-primary text-primary-content shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all uppercase text-[10px] tracking-widest border-none">
                    Atualizar Dados
                </button>
            </div>
        </form>
    </div>

    {{-- Backdrop (Clique fora para fechar + Blur) --}}
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10">
        <button class="cursor-default outline-none">close</button>
    </form>
</dialog>