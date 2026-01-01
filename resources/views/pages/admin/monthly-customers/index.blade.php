@extends('components.layouts.app')

@section('title', 'Mensalistas')

@section('content')
{{-- Cabeçalho e Botão Novo --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-black tracking-tight text-base-content">Gestão de Mensalistas</h1>
        <p class="text-base-content/60 text-sm">Controle de clientes fidelizados e cobranças.</p>
    </div>

    <button onclick="new_customer_modal.showModal()" class="btn btn-primary rounded-xl px-6 font-bold uppercase text-[10px] tracking-widest shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95">
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
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Busque por nome, CPF ou placa..."
                class="w-full pl-12 pr-12 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-base-content placeholder:text-base-content/30 placeholder:text-sm" />
        </div>
    </form>
</div>

{{-- Tabela --}}
<div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-base-200/50">
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
                    {{-- Nome --}}
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

                    {{-- CPF (Coluna real: document_number) --}}
                    <td class="text-center font-medium text-xs opacity-70">
                        {{ preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $customer->document_number) }}
                    </td>

                    <td class="text-center">
                        <div class="flex flex-col justify-center min-h-[40px]">
                            @if($customer->phone)
                            <span class="text-xs font-bold text-base-content tracking-tight">
                                {{ preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $customer->phone) }}
                            </span>
                            @endif
                            <span class="text-[10px] opacity-40 lowercase tracking-wide">
                                {{ $customer->email }}
                            </span>
                        </div>
                    </td>

                    {{-- Status --}}
                    <td class="text-center">
                        @if($customer->is_active)
                        <div class="badge badge-success badge-sm gap-1 py-3 px-3 border-none font-bold uppercase text-[9px] bg-green-500/10 text-green-600">
                            Ativo
                        </div>
                        @else
                        <div class="badge badge-ghost badge-sm gap-1 py-3 px-3 border-none font-bold uppercase text-[9px] opacity-40">
                            Inativo
                        </div>
                        @endif
                    </td>

                    {{-- Ações Sempre Visíveis --}}
                    <td class="text-right pr-6">
                        <div class="flex justify-end gap-2">
                            {{-- Botão Editar --}}
                            <button
                                type="button"
                                onclick="openEditModal(this)"
                                data-id="{{ $customer->id }}"
                                data-name="{{ $customer->first_name }} {{ $customer->last_name }}"
                                data-cpf="{{ $customer->document_number }}"
                                data-phone="{{ $customer->phone }}"
                                data-email="{{ $customer->email }}"
                                data-zip="{{ $customer->zip_code }}"
                                data-address="{{ $customer->address }}"
                                data-due="{{ $customer->due_day }}"
                                data-active="{{ $customer->is_active ? 'active' : 'inactive' }}"
                                class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-white/5 dark:text-white dark:hover:bg-white dark:hover:text-[#070708]"
                                title="Editar Mensalista">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            </button>

                            {{-- Botão Excluir --}}
                            <form action="{{ route('admin.monthly_customers.destroy', $customer->id) }}" method="POST"
                                onsubmit="return confirm('Atenção: Esta ação não pode ser desfeita. Confirmar exclusão?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group border-none outline-none bg-red-600/10 text-red-600 hover:bg-red-600 hover:text-white dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white"
                                    title="Excluir Mensalista">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-24 text-center">
                        <div class="flex flex-col items-center justify-center opacity-20">
                            <i data-lucide="users" class="w-16 h-16 mb-4"></i>
                            <p class="font-black uppercase tracking-widest text-xs">Nenhum mensalista cadastrado</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL NOVO MENSALISTA --}}
<dialog id="new_customer_modal" class="modal modal-bottom sm:modal-middle transition-all duration-500"
    x-data="{ 
        cpf: '', 
        zip: '',
        address: '',
        loading: false,

        maskCPF(v) {
            v = v.replace(/\D/g, '');
            if (v.length <= 11) {
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            return v;
        },

        maskCEP(v) {
            v = v.replace(/\D/g, '');
            v = v.replace(/^(\d{5})(\d)/, '$1-$2');
            return v.substring(0, 9);
        },

        async checkCEP() {
            if (this.zip.length === 9) {
                this.loading = true;
                const cleanZip = this.zip.replace(/\D/g, '');
                try {
                    const response = await fetch(`https://viacep.com.br/ws/${cleanZip}/json/`);
                    const data = await response.json();
                    if (!data.erro) {
                        this.address = `${data.logradouro}, ${data.bairro} - ${data.localidade}/${data.uf}`;
                    }
                } catch (error) { console.error('Erro ViaCEP'); }
                finally { this.loading = false; }
            }
        }
    }">

    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col shadow-2xl border border-base-content/5">
        {{-- Header --}}
        <div class="px-8 py-6 border-b border-base-200 bg-base-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-xl font-bold tracking-tighter text-base-content">Novo Mensalista</h3>
                <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Cadastro de Identificação e Cobrança</p>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>

        <form action="{{ route('admin.monthly_customers.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf

            {{-- Body com Scroll interno --}}
            <div class="flex-1 overflow-y-auto p-8 space-y-6">

                {{-- Nome --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1">
                        <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Nome Completo</span>
                    </label>
                    <input type="text" name="name" required
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm shadow-sm" />
                </div>

                {{-- CPF e RG --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">CPF</span></label>
                        <input type="text" name="cpf" x-model="cpf" @input="cpf = maskCPF($event.target.value)" maxlength="14" placeholder="000.000.000-00" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm shadow-sm" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">RG (Opcional)</span></label>
                        <input type="text" name="id_card"
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm shadow-sm" />
                    </div>
                </div>

                {{-- CEP e Endereço --}}
                <div class="grid grid-cols-12 gap-4">
                    <div class="form-control col-span-12 sm:col-span-4 relative">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">CEP</span></label>
                        <input type="text" name="zip_code" x-model="zip" @input="zip = maskCEP($event.target.value); checkCEP()" maxlength="9" placeholder="00000-000"
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm shadow-sm" />
                        <div x-show="loading" class="absolute right-4 bottom-3">
                            <span class="loading loading-spinner loading-xs text-primary opacity-50"></span>
                        </div>
                    </div>

                    <div class="form-control col-span-12 sm:col-span-8">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Endereço Completo</span></label>
                        <input type="text" name="address" x-model="address" placeholder="Rua, Número, Bairro"
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm shadow-sm" />
                    </div>
                </div>

                {{-- FINANCEIRO E STATUS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- DIA DE VENCIMENTO --}}
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Dia de Vencimento</span>
                        </label>
                        <select name="due_day" required
                            class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-xs font-bold px-5">
                            <option value="">Selecione o dia</option>
                            @for ($i = 1; $i <= 28; $i++)
                                <option value="{{ $i }}">Dia {{ sprintf('%02d', $i) }}</option>
                                @endfor
                        </select>
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Status do Cadastro</span>
                        </label>
                        <select name="is_active"
                            class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-[10px] font-bold uppercase px-5">
                            <option value="active" selected>🟢 Ativo</option>
                            <option value="inactive">🔴 Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="p-6 bg-base-100 border-t border-base-200/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="new_customer_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30 hover:opacity-100 transition-all">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                    Salvar Mensalista
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10"><button>close</button></form>
</dialog>

{{-- MODAL EDITAR MENSALISTA --}}
<dialog id="edit_customer_modal" class="modal modal-bottom sm:modal-middle transition-all duration-500"
    x-data="{ 
        maskCPF(v) {
            if(!v) return '';
            v = v.replace(/\D/g, '');
            if (v.length <= 11) {
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d)/, '$1.$2');
                v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            return v;
        },
        maskPhone(v) {
            if(!v) return '';
            v = v.replace(/\D/g, '');
            v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
            v = v.replace(/(\d)(\d{4})$/, '$1-$2');
            return v.substring(0, 15);
        }
    }">

    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col shadow-2xl border border-base-content/5">
        {{-- Header --}}
        <div class="px-8 py-6 border-b border-base-200 bg-base-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-xl font-bold tracking-tighter text-base-content">Editar Mensalista</h3>
                <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Atualize os dados cadastrais</p>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>

        <form id="edit_customer_form" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')

            <div class="flex-1 overflow-y-auto p-8 space-y-6">
                {{-- Nome --}}
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Nome Completo</span></label>
                    <input type="text" name="name" id="edit_name" required
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm shadow-sm" />
                </div>

                {{-- Email e Telefone --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">E-mail</span></label>
                        <input type="email" name="email" id="edit_email" required
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm shadow-sm" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Telefone</span></label>
                        <input type="text" name="phone" id="edit_phone"
                            @input="$el.value = maskPhone($el.value)"
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm shadow-sm" />
                    </div>
                </div>

                {{-- CPF e Endereço --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">CPF</span></label>
                        <input type="text" name="cpf" id="edit_cpf"
                            @input="$el.value = maskCPF($el.value)"
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm shadow-sm" />
                    </div>
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Endereço</span></label>
                        <input type="text" name="address" id="edit_address"
                            class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-sm shadow-sm" />
                    </div>
                </div>

                {{-- Financeiro e Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Dia de Vencimento</span></label>
                        <select name="due_day" id="edit_due_day" required
                            class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-xs font-bold px-5">
                            @for ($i = 1; $i <= 28; $i++)
                                <option value="{{ $i }}">Dia {{ sprintf('%02d', $i) }}</option>
                                @endfor
                        </select>
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Status</span></label>
                        <select name="is_active" id="edit_is_active"
                            class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-[10px] font-bold uppercase px-5">
                            <option value="active">🟢 Ativo</option>
                            <option value="inactive">🔴 Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <div class="p-6 bg-base-100 border-t border-base-200/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="edit_customer_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30 hover:opacity-100 transition-all">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                    Atualizar Dados
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10"><button>close</button></form>
</dialog>
@endsection

<script>
    function openEditModal(button) {
        const id = button.dataset.id;
        const form = document.getElementById('edit_customer_form');

        form.action = `/admin/mensalistas/${id}`;

        document.getElementById('edit_name').value = button.dataset.name;
        document.getElementById('edit_email').value = button.dataset.email;
        document.getElementById('edit_address').value = button.dataset.address;
        document.getElementById('edit_due_day').value = button.dataset.due;
        document.getElementById('edit_is_active').value = button.dataset.active;

        const cpfInput = document.getElementById('edit_cpf');
        const phoneInput = document.getElementById('edit_phone');

        cpfInput.value = button.dataset.cpf;
        phoneInput.value = button.dataset.phone;

        cpfInput.dispatchEvent(new Event('input'));
        phoneInput.dispatchEvent(new Event('input'));

        document.getElementById('edit_customer_modal').showModal();
    }
</script>