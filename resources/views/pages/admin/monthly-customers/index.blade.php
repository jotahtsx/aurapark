@extends('components.layouts.app')

@section('title', 'Mensalistas')

@section('content')
{{-- Cabeçalho --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-black tracking-tight text-base-content">Gestão de Mensalistas</h1>
        <p class="text-base-content/60 text-sm">Controle de clientes fidelizados e cobranças.</p>
    </div>

    <button onclick="document.getElementById('new_customer_modal').showModal()" class="btn btn-primary rounded-xl px-6 font-bold uppercase text-[10px] tracking-widest shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95">
        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
        Novo Mensalista
    </button>
</div>

{{-- Filtros --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
    <form action="{{ route('admin.monthly_customers.index') }}" method="GET" class="w-full max-w-md group">
        <div class="relative">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-base-content/30 group-focus-within:text-primary transition-all duration-300"></i>
            </div>
            {{-- Input de busca com foco soft --}}
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Busque por nome ou CPF"
                class="w-full pl-12 pr-12 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-200/60 focus:outline-none shadow-sm transition-all duration-300 text-base-content placeholder:text-base-content/30 placeholder:text-sm" />
        </div>
    </form>
</div>

{{-- Tabela --}}
<div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table w-full border-none">
            <thead>
                <tr class="bg-base-200/50 border-b border-base-300">
                    <th class="text-xs uppercase tracking-wider font-semibold pl-6 h-12 text-base-content/60">Nome</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center h-12 text-base-content/60">CPF</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center h-12 text-base-content/60">Contato</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center h-12 text-base-content/60">Status</th>
                    <th class="text-right text-xs uppercase tracking-wider font-semibold pr-6 h-12 text-base-content/60">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base-300">
                @forelse($customers as $customer)
                <tr class="hover:bg-base-200/40 transition-colors text-base-content">
                    <td class="pl-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center text-primary font-bold text-xs shadow-sm">
                                {{ substr($customer->first_name, 0, 1) }}
                            </div>
                            <div class="flex flex-col">
                                <span class="font-bold text-sm tracking-tight leading-none">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                                <span class="text-[10px] opacity-40 font-medium uppercase tracking-tighter mt-1 italic">Mensalista</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-center font-medium text-xs opacity-70">
                        {{ preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $customer->document_number) }}
                    </td>
                    <td class="text-center">
                        <div class="flex flex-col justify-center min-h-[40px]">
                            <span class="text-xs font-bold text-base-content tracking-tight">{{ $customer->phone }}</span>
                            <span class="text-[10px] opacity-40 lowercase tracking-wide">{{ $customer->email }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        <div class="badge {{ $customer->is_active ? 'bg-green-500/10 text-green-600' : 'bg-base-200 opacity-40' }} badge-sm py-3 px-3 border-none font-bold uppercase text-[9px]">
                            {{ $customer->is_active ? 'Ativo' : 'Inativo' }}
                        </div>
                    </td>
                    <td class="text-right pr-6">
                        <div class="flex justify-end gap-2">
                            {{-- Botão Editar - Design Mecânico --}}
                            <button type="button" onclick="openEditModal(this)"
                                data-id="{{ $customer->id }}"
                                data-name="{{ $customer->first_name }} {{ $customer->last_name }}"
                                data-email="{{ $customer->email }}"
                                data-cpf="{{ $customer->document_number }}"
                                data-phone="{{ $customer->phone }}"
                                data-due="{{ $customer->due_day }}"
                                data-active="{{ $customer->is_active ? 'active' : 'inactive' }}"
                                class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-white/5 dark:text-white dark:hover:bg-white dark:hover:text-[#070708]">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            </button>

                            <form action="{{ route('admin.monthly_customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Confirmar exclusão?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group border-none outline-none bg-red-600/10 text-red-600 hover:bg-red-600 hover:text-white dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-28 text-center">
                        <div class="flex flex-col items-center justify-center w-full">

                            <div class="w-20 h-20 bg-base-200 rounded-full flex items-center justify-center mb-6">
                                <i data-lucide="users-round" class="w-10 h-10 opacity-20 text-base-content"></i>
                            </div>

                            <div class="max-w-xs mx-auto text-center">
                                <h3 class="font-black text-lg text-base-content/50 tracking-tight">
                                    Nenhum mensalista encontrado
                                </h3>
                                <p class="text-[12px] text-base-content/30 italic mt-2 leading-relaxed">
                                    Sua busca não retornou resultados. <br>
                                    Tente outros termos ou cadastre um novo mensalista.
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

{{-- MODAL NOVO MENSALISTA --}}
<dialog id="new_customer_modal" class="modal modal-bottom sm:modal-middle transition-all duration-500">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col border border-base-content/5 shadow-2xl"
        x-data="{ 
            cpf: '', tel: '',
            maskCPF(v) { v = v.replace(/\D/g, ''); return v.replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2').substring(0,14); },
            maskPhone(v) { v = v.replace(/\D/g, ''); return v.replace(/^(\d{2})(\d)/g, '($1) $2').replace(/(\d)(\d{4})$/, '$1-$2').substring(0, 15); }
        }">

        <div class="px-8 py-6 border-b border-base-200 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold tracking-tighter">Novo Mensalista</h3>
                <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Cadastro de Identificação</p>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>

        <form action="{{ route('admin.monthly_customers.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="p-8 space-y-6 overflow-y-auto max-h-[60vh]">

                {{-- Nome --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Nome Completo</span></label>
                    <input type="text" name="name" required
                        class="w-full px-5 h-12 !bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 !focus:bg-base-200/70 focus:outline-none transition-all text-sm shadow-sm text-base-content" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Telefone --}}
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Telefone</span></label>
                        <input type="text" x-model="tel" @input="tel = maskPhone($event.target.value)" name="phone" placeholder="(00) 00000-0000"
                            class="w-full px-5 h-12 !bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 !focus:bg-base-200/70 focus:outline-none transition-all text-sm shadow-sm text-base-content" />
                    </div>
                    {{-- CPF --}}
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">CPF</span></label>
                        <input type="text" x-model="cpf" @input="cpf = maskCPF($event.target.value)" name="cpf" maxlength="14" required
                            class="w-full px-5 h-12 !bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 !focus:bg-base-200/70 focus:outline-none transition-all text-sm shadow-sm text-base-content" />
                    </div>
                </div>

                {{-- E-mail --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">E-mail</span></label>
                    <input type="email" name="email" required
                        class="w-full px-5 h-12 !bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 !focus:bg-base-200/70 focus:outline-none transition-all text-sm shadow-sm text-base-content" />
                </div>

                {{-- DIA DE VENCIMENTO (Recuperado) --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Dia de Vencimento</span></label>
                    <div class="flex flex-wrap gap-2 p-4 bg-base-200/50 rounded-3xl group-focus-within:ring-4 group-focus-within:ring-primary/10 transition-all">
                        @for ($i = 1; $i <= 28; $i++)
                            <label class="relative flex-1 min-w-[45px]">
                            <input type="radio" name="due_day" value="{{ $i }}" {{ $i == 10 ? 'checked' : '' }} class="peer absolute opacity-0 w-full h-full cursor-pointer" required />
                            <div class="h-10 w-full flex items-center justify-center rounded-xl bg-base-100 border border-base-300/50 text-[11px] font-bold transition-all peer-checked:bg-primary peer-checked:text-primary-content peer-checked:shadow-lg peer-checked:border-primary hover:bg-base-300">
                                {{ sprintf('%02d', $i) }}
                            </div>
                            </label>
                            @endfor
                    </div>
                </div>

                {{-- STATUS (Active/Inactive) --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1">
                        <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">
                            Status da Assinatura
                        </span>
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="is_active" value="active" checked class="peer hidden" />
                            <div class="py-2.5 rounded-xl bg-base-200/50 text-center text-[9px] font-black uppercase tracking-widest border border-transparent peer-checked:border-primary/20 peer-checked:bg-primary/5 transition-all text-base-content/40 peer-checked:text-primary">
                                🟢 Ativo
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="is_active" value="inactive" class="peer hidden" />
                            <div class="py-2.5 rounded-xl bg-base-200/50 text-center text-[9px] font-black uppercase tracking-widest border border-transparent peer-checked:border-primary/20 peer-checked:bg-primary/5 transition-all text-base-content/40 peer-checked:text-primary">
                                🔴 Inativo
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-base-100 border-t border-base-200/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="new_customer_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all">Salvar Mensalista</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-base-content/10 backdrop-blur-md transition-all duration-500"><button>close</button></form>
</dialog>

{{-- MODAL EDIÇÃO MENSALISTA --}}
<dialog id="edit_customer_modal" class="modal modal-bottom sm:modal-middle transition-all duration-500">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col border border-base-content/5 shadow-2xl">

        {{-- Cabeçalho --}}
        <div class="px-8 py-6 border-b border-base-200 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-bold tracking-tighter text-base-content">Editar Mensalista</h3>
                <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Atualização de Dados</p>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>

        {{-- FORMULÁRIO COM AS CHAVES DE ATUALIZAÇÃO --}}
        <form id="edit_customer_form"
            method="POST"
            data-action="{{ route('admin.monthly_customers.update', ['customer' => ':id']) }}"
            class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-6 overflow-y-auto max-h-[60vh]">

                {{-- Nome --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Nome Completo</span></label>
                    <input type="text" id="edit_name" name="name" required
                        class="w-full px-5 h-12 !bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 !focus:bg-base-200/70 focus:outline-none transition-all text-sm text-base-content" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Telefone --}}
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Telefone</span></label>
                        <input type="text" id="edit_phone" name="phone"
                            class="w-full px-5 h-12 !bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 !focus:bg-base-200/70 focus:outline-none transition-all text-sm text-base-content" />
                    </div>
                    {{-- CPF --}}
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">CPF</span></label>
                        <input type="text" id="edit_cpf" name="cpf" maxlength="14" required
                            class="w-full px-5 h-12 !bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 !focus:bg-base-200/70 focus:outline-none transition-all text-sm text-base-content" />
                    </div>
                </div>

                {{-- E-mail --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">E-mail</span></label>
                    <input type="email" id="edit_email" name="email" required
                        class="w-full px-5 h-12 !bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 !focus:bg-base-200/70 focus:outline-none transition-all text-sm text-base-content" />
                </div>

                {{-- Vencimento --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Dia de Vencimento</span></label>
                    <div class="flex flex-wrap gap-2 p-4 bg-base-200/50 rounded-3xl">
                        @for ($i = 1; $i <= 28; $i++)
                            <label class="relative flex-1 min-w-[45px]">
                            <input type="radio" name="due_day" id="edit_due_{{ $i }}" value="{{ $i }}" class="peer absolute opacity-0 w-full h-full cursor-pointer" />
                            <div class="h-10 w-full flex items-center justify-center rounded-xl bg-base-100 border border-base-300/50 text-[11px] font-bold text-base-content transition-all peer-checked:bg-primary peer-checked:text-primary-content peer-checked:border-primary hover:bg-base-300">
                                {{ sprintf('%02d', $i) }}
                            </div>
                            </label>
                            @endfor
                    </div>
                </div>

                {{-- Status Slim --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Status da Assinatura</span></label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="cursor-pointer">
                            <input type="radio" name="is_active" id="edit_status_active" value="active" class="peer hidden" />
                            <div class="py-2.5 rounded-xl bg-base-200/50 text-center text-[9px] font-black uppercase tracking-widest border border-transparent peer-checked:border-primary/20 peer-checked:bg-primary/5 transition-all text-base-content/40 peer-checked:text-primary">🟢 Ativo</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="is_active" id="edit_status_inactive" value="inactive" class="peer hidden" />
                            <div class="py-2.5 rounded-xl bg-base-200/50 text-center text-[9px] font-black uppercase tracking-widest border border-transparent peer-checked:border-primary/20 peer-checked:bg-primary/5 transition-all text-base-content/40 peer-checked:text-primary">🔴 Inativo</div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Botões --}}
            <div class="p-6 bg-base-100 border-t border-base-200/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="edit_customer_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all">Salvar Alterações</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop bg-base-content/10 backdrop-blur-md"><button>close</button></form>
</dialog>

<script>
    function formatarCPF(v) {
        v = v.replace(/\D/g, "");
        if (v.length > 11) v = v.substring(0, 11);
        return v.replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d)/, "$1.$2")
                .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    }

    function openEditModal(button) {
        const id = button.dataset.id;
        const form = document.getElementById('edit_customer_form');

        const baseAction = form.dataset.action;
        form.action = baseAction.replace(':id', id);

        document.getElementById('edit_name').value = button.dataset.name;
        document.getElementById('edit_email').value = button.dataset.email;
        
        const cpfBruto = button.dataset.cpf;
        document.getElementById('edit_cpf').value = formatarCPF(cpfBruto);
        
        document.getElementById('edit_phone').value = button.dataset.phone;

        const dueDay = button.dataset.due;
        const radioDue = document.getElementById('edit_due_' + dueDay);
        if (radioDue) radioDue.checked = true;

        if (button.dataset.active === 'active') {
            document.getElementById('edit_status_active').checked = true;
        } else {
            document.getElementById('edit_status_inactive').checked = true;
        }

        edit_customer_modal.showModal();
    }

    document.getElementById('edit_cpf').addEventListener('input', function(e) {
        e.target.value = formatarCPF(e.target.value);
    });
</script>
@endsection