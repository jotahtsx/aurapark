@extends('components.layouts.app')

@section('title', 'Precificações — Gestão de Precificações')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-base-content font-black tracking-tight">Gestão de Precificações</h1>
        <p class="text-base-content/60 text-sm">Gerencie as tarifas e categorias de estacionamento.</p>
    </div>

    <a href="#" onclick="event.preventDefault(); new_pricing_modal.showModal()" class="btn btn-primary shadow-lg shadow-primary/20 rounded-xl px-6 font-bold uppercase text-[10px] tracking-widest">
        <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
        Nova Precificação
    </a>
</div>

{{-- Filtros e Busca --}}
<div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
    <form action="{{ route('admin.pricings.index') }}" method="GET" class="w-full max-w-md group">
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
        </div>
    </form>

    <div class="hidden md:flex gap-4 text-right">
        <div class="flex flex-col items-end">
            <span class="text-[10px] font-bold uppercase opacity-40 tracking-widest">Registros</span>
            <span class="text-sm font-semibold text-base-content">{{ $pricings->count() }} categorias</span>
        </div>
    </div>
</div>

{{-- Tabela --}}
<div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-200/50">
                    <th class="text-xs uppercase tracking-wider font-semibold pl-6">Categoria</th>
                    <th class="text-xs uppercase tracking-wider font-semibold">Valor Hora</th>
                    <th class="text-xs uppercase tracking-wider font-semibold">Mensalidade</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center">Vagas</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center">Status</th>
                    <th class="text-right text-xs uppercase tracking-wider font-semibold pr-6">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base-300">
                @forelse($pricings as $price)
                <tr class="hover:bg-base-200/40 transition-colors group">
                    <td class="pl-6">
                        <div class="flex items-center gap-4">
                            <div>
                                <div class="font-bold text-base-content">{{ $price->category }}</div>
                                <div class="text-[10px] opacity-40 font-bold uppercase">ID #{{ $price->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm font-medium">R$ {{ number_format($price->hourly_price, 2, ',', '.') }}</td>
                    <td class="text-sm font-medium">R$ {{ number_format($price->monthly_price, 2, ',', '.') }}</td>
                    <td class="text-center font-bold text-xs opacity-60">{{ $price->total_spots }}</td>
                    <td class="text-center">
                        <div class="badge {{ $price->is_active ? 'badge-success' : 'badge-ghost opacity-60' }} badge-sm py-3 px-3 font-bold uppercase text-[9px] border-none">
                            {{ $price->is_active ? 'Ativo' : 'Inativo' }}
                        </div>
                    </td>
                    <th class="text-right pr-6">
                        <div class="flex justify-end gap-1">
                            <a href="javascript:void(0)"
                                @click="$dispatch('edit-pricing', { 
                                    id: {{ $price->id }}, 
                                    category: '{{ $price->category }}', 
                                    hourly_price: '{{ $price->hourly_price }}', 
                                    monthly_price: '{{ $price->monthly_price }}', 
                                    total_spots: '{{ $price->total_spots }}',
                                    is_active: {{ $price->is_active ? 'true' : 'false' }}
                                })"
                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white dark:bg-white/5 dark:text-white transition-all duration-300 group">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110"></i>
                            </a>

                            <form action="{{ route('admin.pricings.destroy', $price->id) }}" method="POST"
                                onsubmit="return confirm('Deseja realmente excluir esta precificação?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-600/10 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300 border-none outline-none group">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110"></i>
                                </button>
                            </form>
                        </div>
                    </th>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-20 text-center opacity-30 italic font-medium">Nenhuma precificação cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<dialog id="new_pricing_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col shadow-2xl border border-base-content/5">
        <div class="px-8 py-6 border-b border-base-200 bg-base-100">
            <h3 class="text-xl font-bold text-base-content tracking-tighter">Cadastrar Precificação</h3>
            <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Defina as tarifas para uma nova categoria</p>
        </div>

        <form action="{{ route('admin.pricings.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            <div class="form-control group">
                <label class="label py-1 ml-1">
                    <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Nome da Categoria</span>
                </label>
                <input type="text" name="category" placeholder="Ex: Carro de Luxo" required
                    class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control group">
                    <label class="label py-1 ml-1">
                        <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Valor por Hora</span>
                    </label>
                    <input type="number" step="0.01" name="hourly_price" placeholder="0.00" required
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>

                <div class="form-control group">
                    <label class="label py-1 ml-1">
                        <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Mensalidade</span>
                    </label>
                    <input type="number" step="0.01" name="monthly_price" placeholder="0.00" required
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>

                <div class="form-control group">
                    <label class="label py-1 ml-1">
                        <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Vagas Totais</span>
                    </label>
                    <input type="number" name="total_spots" placeholder="0" required
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>
            </div>

            {{-- Ações --}}
            <div class="flex items-center justify-end gap-3 pt-4 bg-base-100 border-t border-base-200/60 mt-4">
                <button type="button" onclick="new_pricing_modal.close()"
                    class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30 hover:opacity-100 transition-all">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-10 py-4 rounded-2xl font-black bg-primary text-primary-content shadow-xl shadow-primary/20 hover:shadow-primary/40 transition-all active:scale-95 uppercase text-[10px] tracking-[0.2em]">
                    Salvar Registro
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10"><button>close</button></form>
</dialog>


<dialog id="edit_pricing_modal" class="modal modal-bottom sm:modal-middle" x-data="{ 
    id: null, category: '', hourly: '', monthly: '', spots: '', active: true 
}" @edit-pricing.window="
    id = $event.detail.id;
    category = $event.detail.category;
    hourly = $event.detail.hourly_price;
    monthly = $event.detail.monthly_price;
    spots = $event.detail.total_spots;
    active = $event.detail.is_active;
    $el.showModal();
">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col shadow-2xl border border-base-content/5 z-10">
        <div class="px-8 py-6 border-b border-base-200 bg-base-100">
            <h3 class="text-xl font-bold text-base-content tracking-tighter">Editar Precificação</h3>
            <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Atualize as tarifas da categoria selecionada</p>
        </div>

        <form :action="'/admin/precificacoes/' + id" method="POST" class="p-8 space-y-6">
            @csrf @method('PUT')

            <div class="form-control group">
                <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Categoria</span></label>
                <input type="text" name="category" x-model="category" required 
                    class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Valor Hora</span></label>
                    <input type="number" step="0.01" name="hourly_price" x-model="hourly" required 
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>
                
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Mensalidade</span></label>
                    <input type="number" step="0.01" name="monthly_price" x-model="monthly" required 
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>

                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Vagas Totais</span></label>
                    <input type="number" name="total_spots" x-model="spots" required 
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>
            </div>

            <div class="form-control group">
                <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Status da Categoria</span></label>
                <select name="is_active" x-model="active" 
                    class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-xs font-bold uppercase px-5">
                    <option :value="true">🟢 Ativo</option>
                    <option :value="false">🔴 Inativo</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-base-200/60 mt-4">
                <button type="button" @click="edit_pricing_modal.close()" 
                    class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30 hover:opacity-100">
                    Cancelar
                </button>
                <button type="submit" 
                    class="px-10 py-4 rounded-2xl font-black bg-primary text-primary-content shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all uppercase text-[10px] tracking-widest">
                    Atualizar Precificação
                </button>
            </div>
        </form>
    </div>

    {{-- Backdrop Form: Adicionado para permitir fechar ao clicar fora e ativar o blur --}}
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10">
        <button>close</button>
    </form>
</dialog>

@endsection