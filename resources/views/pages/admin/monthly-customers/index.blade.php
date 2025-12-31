@extends('components.layouts.app')

@section('title', 'Mensalistas')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-black tracking-tight text-base-content">Gestão de Mensalistas</h1>
        <p class="text-base-content/60 text-sm">Controle de clientes fidelizados e cobranças.</p>
    </div>

    <button onclick="new_customer_modal.showModal()" class="btn btn-primary rounded-xl font-bold uppercase text-[10px] tracking-widest">
        <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
        Novo Mensalista
    </button>
</div>

<div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
    <form action="{{ route('admin.monthly_customers.index') }}" method="GET" class="w-full max-w-md group">
        <div class="relative">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-base-content/30 group-focus-within:text-primary transition-all duration-300"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Busque por nome ou CPF..."
                class="input w-full pl-12 pr-12 h-12 bg-base-200/50 border-none rounded-2xl 
                       focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none
                       shadow-sm transition-all duration-300 text-base-content 
                       placeholder:text-base-content/30 placeholder:text-sm placeholder:font-light" />
        </div>
    </form>

    <div class="hidden md:flex gap-4 text-right">
        <div class="flex flex-col items-end">
            <span class="text-[10px] font-bold uppercase opacity-40 tracking-widest">Registros</span>
            <span class="text-sm font-semibold text-base-content">{{ $customers->total() }} mensalistas cadastrados</span>
        </div>
    </div>
</div>

<div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr class="bg-base-200/50">
                    <th class="text-xs uppercase tracking-wider font-semibold pl-6 w-16">#</th>
                    <th class="text-xs uppercase tracking-wider font-semibold">Nome Completo</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center">CPF</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center">Email</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center">Contato</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center">Status</th>
                    <th class="text-right text-xs uppercase tracking-wider font-semibold pr-6">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base-300">
                @forelse($customers as $customer)
                <tr class="hover:bg-base-200/40 transition-colors group text-base-content">
                    <td class="pl-6 font-medium opacity-50">#{{ $customer->id }}</td>
                    <td class="font-bold text-base">
                        {{ $customer->first_name }} {{ $customer->last_name }}
                    </td>
                    <td class="text-center font-medium">{{ $customer->document_number }}</td>
                    <td class="text-center lowercase text-sm opacity-70">{{ $customer->email }}</td>
                    <td class="text-center text-sm">{{ $customer->phone }}</td>
                    <td class="text-center">
                        @if($customer->is_active)
                            <div class="badge badge-success badge-sm gap-1 py-3 px-3 border-none font-bold uppercase text-[9px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Ativo
                            </div>
                        @else
                            <div class="badge badge-ghost badge-sm gap-1 py-3 px-3 opacity-60 border-none font-bold uppercase text-[9px]">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span> Inativo
                            </div>
                        @endif
                    </td>
                    <td class="text-right pr-6">
                        <div class="flex justify-end gap-2">
                            <button @click="$dispatch('edit-monthly-customer', { 
                                id: {{ $customer->id }}, 
                                first_name: '{{ $customer->first_name }}', 
                                last_name: '{{ $customer->last_name }}', 
                                is_active: {{ $customer->is_active ? 1 : 0 }} 
                            })"
                                class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group
                               bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white
                               dark:bg-white/5 dark:text-white dark:hover:bg-white dark:hover:text-[#070708]"
                                title="Editar Mensalista">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            </button>

                            <form action="{{ route('admin.monthly_customers.destroy', $customer->id) }}" method="POST"
                                onsubmit="return confirm('Deseja realmente excluir este mensalista?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group border-none outline-none
                                   bg-red-600/10 text-red-600 hover:bg-red-600 hover:text-white
                                   dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white"
                                    title="Excluir Mensalista">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <div class="w-16 h-16 bg-base-200 rounded-full flex items-center justify-center">
                                <i data-lucide="search-x" class="w-8 h-8 opacity-20"></i>
                            </div>
                            <div class="max-w-xs">
                                <p class="font-bold text-base-content/50">Nenhum mensalista encontrado</p>
                                <p class="text-xs text-base-content/30 italic">Tente ajustar sua busca ou cadastre um novo cliente.</p>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($customers->hasPages())
        <div class="p-4 border-t border-base-300 bg-base-200/20">
            {{ $customers->links() }}
        </div>
    @endif
</div>

@endsection

<dialog id="new_customer_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box p-0 max-w-4xl bg-base-100 rounded-3xl overflow-hidden flex flex-col shadow-2xl border border-base-content/5">
        
        <div class="px-8 py-6 border-b border-base-200 bg-base-100 flex justify-between items-center shrink-0">
            <div>
                <h3 class="text-xl font-bold tracking-tighter text-base-content">Cadastrar Mensalista</h3>
                <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Novo contrato de estacionamento</p>
            </div>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>

        <form action="{{ route('admin.monthly_customers.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            <div class="flex-1 overflow-y-auto p-8 pt-6 space-y-8 custom-scrollbar">
                
                {{-- DADOS PESSOAIS --}}
                <div class="grid grid-cols-1 md:grid-cols-12 gap-x-6 gap-y-5">
                    <div class="form-control md:col-span-8 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Nome Completo</span>
                        </label>
                        <input type="text" name="name" placeholder="Ex: João Silva" required
                            class="input w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-sm text-base-content" />
                    </div>

                    <div class="form-control md:col-span-4 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">CPF</span>
                        </label>
                        <input type="text" name="cpf" placeholder="000.000.000-00" required
                            class="input w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-sm text-base-content" />
                    </div>

                    <div class="form-control md:col-span-7 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">E-mail</span>
                        </label>
                        <input type="email" name="email" placeholder="cliente@email.com"
                            class="input w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-sm text-base-content" />
                    </div>

                    <div class="form-control md:col-span-5 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Telefone</span>
                        </label>
                        <input type="text" name="phone" placeholder="(00) 00000-0000"
                            class="input w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-sm text-base-content" />
                    </div>
                </div>

                <div class="h-px bg-base-200/60 w-full"></div>

                {{-- VEÍCULO E CONTRATO --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-5">
                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Modelo do Veículo</span>
                        </label>
                        <input type="text" name="vehicle_model" placeholder="Ex: Honda Civic" required
                            class="input w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-sm text-base-content" />
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Placa</span>
                        </label>
                        <input type="text" name="vehicle_plate" placeholder="ABC-1234" required
                            class="input w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-sm text-base-content uppercase" />
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Vencimento (Dia)</span>
                        </label>
                        <input type="number" name="due_day" placeholder="Ex: 10" min="1" max="31" required
                            class="input w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-sm text-base-content" />
                    </div>

                    <div class="form-control group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Mensalidade (R$)</span>
                        </label>
                        <input type="number" step="0.01" name="monthly_fee" placeholder="0,00" required
                            class="input w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-sm text-base-content font-bold" />
                    </div>

                    <div class="form-control md:col-span-2 group">
                        <label class="label py-1 ml-1">
                            <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Status Inicial</span>
                        </label>
                        <select name="status" class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none transition-all text-xs font-bold uppercase px-5 text-base-content">
                            <option value="active">🟢 Ativo</option>
                            <option value="inactive">🔴 Inativo</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-base-100 border-t border-base-200/60 flex items-center justify-end gap-3 shrink-0">
                <button type="button" onclick="new_customer_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30 hover:opacity-100 transition-all">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20 hover:scale-105 transition-transform">
                    Salvar Mensalista
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/20"><button>close</button></form>
</dialog>