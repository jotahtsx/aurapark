@extends('components.layouts.app')

@section('title', 'Usuários')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-black tracking-tight text-base-content">Gestão de Usuários</h1>
        <p class="text-base-content/60 text-sm">Visualize e gerencie todos os usuários cadastrados.</p>
    </div>

    <button onclick="new_user_modal.showModal()" 
        class="btn btn-primary shadow-lg shadow-primary/20 rounded-xl px-6 font-bold uppercase text-[10px] tracking-widest transition-all hover:scale-105 active:scale-95">
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

            <input type="text" name="search" value="{{ request('search') }}"
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
        <table class="table w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-base-200/50">
                    <th class="text-xs uppercase tracking-wider font-semibold pl-6 h-12">Usuário</th>
                    <th class="text-xs uppercase tracking-wider font-semibold h-12 text-center">Status</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center h-12">Data de Cadastro</th>
                    <th class="text-right text-xs uppercase tracking-wider font-semibold pr-6 h-12">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base-300">
                @forelse($users as $user)
                <tr class="hover:bg-base-200/40 transition-colors group">
                    <td class="pl-6">
                        <div class="flex items-center gap-4 py-1">
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
                                <div class="font-bold text-base text-base-content">{{ $user->name }} {{ $user->last_name }}</div>
                                <div class="text-[11px] text-base-content/50 font-medium">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($user->status === 'active')
                        <div class="badge badge-success badge-sm gap-1 py-3 px-3 border-none font-bold uppercase text-[9px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span>
                            Ativo
                        </div>
                        @else
                        <div class="badge badge-ghost badge-sm gap-1 py-3 px-3 opacity-60 border-none font-bold uppercase text-[9px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            Inativo
                        </div>
                        @endif
                    </td>
                    <td class="text-[11px] font-bold text-base-content/40 text-center uppercase tracking-tighter">
                        {{ $user->created_at->translatedFormat('d M, Y') }}
                    </td>
                    <td class="text-right pr-6">
                        <div class="flex justify-end gap-2">
                            <button @click="$dispatch('edit-user', { 
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
                            </button>

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
                            <div class="w-9 h-9 flex items-center justify-center rounded-xl bg-base-200 text-base-content/20 cursor-help"
                                title="Proteção de conta: Você não pode se excluir.">
                                <i data-lucide="shield-check" class="w-4 h-4"></i>
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <div class="w-16 h-16 bg-base-200 rounded-full flex items-center justify-center">
                                <i data-lucide="user-x" class="w-8 h-8 opacity-20"></i>
                            </div>
                            <div class="max-w-xs mx-auto">
                                <p class="font-bold text-base-content/50">Nenhum usuário encontrado</p>
                                <p class="text-[11px] text-base-content/30 italic mt-1 leading-relaxed">
                                    Tente ajustar os termos da sua busca para localizar um colaborador.
                                </p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL NOVO USUÁRIO --}}
<dialog id="new_user_modal" class="modal modal-bottom sm:modal-middle transition-all duration-500">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col shadow-2xl border border-base-content/5 transform transition-all">
        <div class="px-8 py-6 border-b border-base-200 bg-base-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-xl font-bold tracking-tighter text-base-content">Cadastrar Usuário</h3>
                <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Habilite um novo acesso ao sistema</p>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data"
            x-data="{ photoPreview: null }" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="flex-1 overflow-y-auto p-8 pt-6 space-y-8 custom-scrollbar">

                <div class="flex flex-col items-center group">
                    <label class="relative cursor-pointer hover:scale-105 active:scale-95 transition-all">
                        <div class="w-24 h-24 rounded-[32px] bg-base-200/50 flex items-center justify-center border-2 border-dashed border-base-content/10 overflow-hidden shadow-inner group-hover:border-primary/30">
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
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm text-base-content" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Sobrenome</span>
                        </label>
                        <input type="text" name="last_name" placeholder="Ex: Oliveira" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm text-base-content" />
                    </div>
                    <div class="form-control md:col-span-2 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">E-mail Corporativo</span>
                        </label>
                        <input type="email" name="email" placeholder="nome@aurapark.com" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm text-base-content" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Senha Inicial</span>
                        </label>
                        <input type="password" name="password" placeholder="••••••••" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm text-base-content" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Status da Conta</span>
                        </label>
                        <select name="status" class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-xs font-bold uppercase px-5 text-base-content">
                            <option value="active">🟢 Ativo</option>
                            <option value="inactive">🔴 Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-base-100 border-t border-base-200/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="new_user_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30 hover:opacity-100 transition-all">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-transform">
                    Salvar Registro
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/20"><button>close</button></form>
</dialog>

{{-- MODAL EDITAR USUÁRIO --}}
<dialog id="edit_user_modal" class="modal modal-bottom sm:modal-middle transition-all" 
    x-data="{ editPhotoPreview: null, userName: '', userEmail: '', userLastName: '', userStatus: 'active', userId: null }"
    @edit-user.window="
        userId = $event.detail.id;
        userName = $event.detail.name;
        userLastName = $event.detail.last_name;
        userEmail = $event.detail.email;
        userStatus = $event.detail.status;
        let avatar = $event.detail.avatar;
        editPhotoPreview = avatar ? (avatar.startsWith('http') ? avatar : '/storage/' + avatar) : null;
        $el.showModal();
    ">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col shadow-2xl border border-base-content/5">
        <div class="px-8 py-6 border-b border-base-200 bg-base-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-xl font-bold tracking-tighter text-base-content">Editar Perfil</h3>
                <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Atualize as credenciais do operador</p>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>

        <form :action="'/admin/users/' + userId" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="flex-1 overflow-y-auto p-8 pt-6 space-y-8 custom-scrollbar">
                <div class="flex flex-col items-center group">
                    <label class="relative cursor-pointer hover:scale-105 active:scale-95 transition-all">
                        <div class="w-24 h-24 rounded-[32px] bg-base-200/50 flex items-center justify-center border-2 border-dashed border-base-content/10 overflow-hidden shadow-inner group-hover:border-primary/30">
                            <template x-if="!editPhotoPreview">
                                <div class="flex flex-col items-center opacity-30">
                                    <i data-lucide="camera" class="w-6 h-6 mb-1 text-primary"></i>
                                    <span class="text-[8px] font-black uppercase">Foto</span>
                                </div>
                            </template>
                            <template x-if="editPhotoPreview">
                                <img :src="editPhotoPreview" class="w-full h-full object-cover">
                            </template>
                        </div>
                        <input type="file" name="avatar" class="hidden" @change="const file = $event.target.files[0]; if (file) { const reader = new FileReader(); reader.onload = (e) => { editPhotoPreview = e.target.result; }; reader.readAsDataURL(file); }">
                        <div class="absolute -bottom-1 -right-1 bg-primary p-1.5 rounded-xl text-white shadow-lg shadow-primary/30">
                            <i data-lucide="refresh-cw" class="w-3 h-3"></i>
                        </div>
                    </label>
                    <p class="text-[10px] text-base-content/20 font-bold uppercase mt-3 tracking-widest">Avatar do Perfil</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Nome</span>
                        </label>
                        <input type="text" name="name" x-model="userName" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 transition-all text-sm text-base-content" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Sobrenome</span>
                        </label>
                        <input type="text" name="last_name" x-model="userLastName" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 transition-all text-sm text-base-content" />
                    </div>
                    <div class="form-control md:col-span-2 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">E-mail</span>
                        </label>
                        <input type="email" name="email" x-model="userEmail" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 transition-all text-sm text-base-content" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Nova Senha</span>
                        </label>
                        <input type="password" name="password" placeholder="Em branco para manter"
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 transition-all text-sm text-base-content placeholder:text-base-content/20" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Status</span>
                        </label>
                        <select name="status" x-model="userStatus" 
                            class="select w-full bg-base-200/50 border-none rounded-2xl h-12 text-[10px] font-bold uppercase px-5 transition-all text-base-content">
                            <option value="active">🟢 Ativo</option>
                            <option value="inactive">🔴 Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-base-100 border-t border-base-200/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="edit_user_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30 hover:opacity-100 transition-all">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-transform">
                    Atualizar Dados
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10"><button>close</button></form>
</dialog>

@endsection