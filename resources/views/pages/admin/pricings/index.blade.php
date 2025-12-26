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
                                <div class="text-[10px] opacity-40 font-bold uppercase tracking-widest">ID #{{ $price->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm font-medium text-base-content/70">R$ {{ number_format($price->hourly_price, 2, ',', '.') }}</td>
                    <td class="text-sm font-medium text-base-content/70">R$ {{ number_format($price->monthly_price, 2, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-base-200 text-[11px] font-bold text-base-content/60">
                            {{ $price->total_spots }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if($price->is_active)
                        <div class="badge badge-success badge-sm gap-1 py-3 px-3 border-none font-bold uppercase text-[9px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            Ativo
                        </div>
                        @else
                        <div class="badge badge-ghost badge-sm gap-1 py-3 px-3 opacity-60 border-none font-bold uppercase text-[9px]">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            Inativo
                        </div>
                        @endif
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
                                class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group
                           bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white
                           dark:bg-white/5 dark:text-white dark:hover:bg-white dark:hover:text-[#070708]"
                                title="Editar Precificação">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            </a>

                            <form action="{{ route('admin.pricings.destroy', $price->id) }}" method="POST"
                                onsubmit="return confirm('Atenção: Esta ação não pode ser desfeita. Confirmar exclusão?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group border-none outline-none
                               bg-red-600/10 text-red-600 hover:bg-red-600 hover:text-white
                               dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white"
                                    title="Excluir Precificação">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </form>
                        </div>
                    </th>
                </tr>
                @empty
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
    id: null, 
    category: '', 
    hourly: '', 
    monthly: '', 
    spots: '', 
    active: 1 
}" @edit-pricing.window="
    id = $event.detail.id;
    category = $event.detail.category;
    hourly = $event.detail.hourly_price;
    monthly = $event.detail.monthly_price;
    spots = $event.detail.total_spots;
    active = $event.detail.is_active ? 1 : 0;
    $el.showModal();
">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden flex flex-col shadow-2xl border border-base-content/5 z-10">

        {{-- Header --}}
        <div class="px-8 py-6 border-b border-base-200 bg-base-100">
            <h3 class="text-xl font-bold text-base-content tracking-tighter">Editar Precificação</h3>
            <p class="text-[10px] text-base-content/40 font-bold uppercase tracking-widest mt-1">Atualize as tarifas da categoria selecionada</p>
        </div>

        <form :action="'/admin/precificacoes/' + id" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            {{-- Campo Categoria --}}
            <div class="form-control group">
                <label class="label py-1 ml-1">
                    <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Nome da Categoria</span>
                </label>
                <input type="text" name="category" x-model="category" required
                    class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="form-control group">
                    <label class="label py-1 ml-1">
                        <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Valor por Hora</span>
                    </label>
                    <input type="number" step="0.01" name="hourly_price" x-model="hourly" required
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>

                <div class="form-control group">
                    <label class="label py-1 ml-1">
                        <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Mensalidade</span>
                    </label>
                    <input type="number" step="0.01" name="monthly_price" x-model="monthly" required
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>

                <div class="form-control group">
                    <label class="label py-1 ml-1">
                        <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Vagas Totais</span>
                    </label>
                    <input type="number" name="total_spots" x-model="spots" required
                        class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-sm" />
                </div>
            </div>

            <div class="form-control group">
                <label class="label py-1 ml-1">
                    <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40 group-focus-within:text-primary transition-all">Status da Categoria</span>
                </label>
                <select name="is_active" x-model="active"
                    class="select w-full bg-base-200/50 border-none rounded-2xl h-12 focus:outline-none focus:ring-4 focus:ring-primary/5 focus:bg-base-100 transition-all text-xs font-bold uppercase px-5">
                    <option value="1">🟢 Ativo</option>
                    <option value="0">🔴 Inativo</option>
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

    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10">
        <button>close</button>
    </form>
</dialog>

@endsection