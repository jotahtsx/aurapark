@extends('components.layouts.app')

@section('title', 'Precificações')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-black tracking-tight text-base-content">Gestão de Precificações</h1>
        <p class="text-base-content/60 text-sm">Gerencie as tarifas e categorias de estacionamento.</p>
    </div>

    <button onclick="new_pricing_modal.showModal()"
        class="btn btn-primary shadow-lg shadow-primary/20 rounded-xl px-6 font-bold uppercase text-[10px] tracking-widest transition-all hover:scale-105 active:scale-95">
        <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
        Nova Precificação
    </button>
</div>

<div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
    <form action="{{ route('admin.pricings.index') }}" method="GET" class="w-full max-w-md group">
        <div class="relative">
            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-5 h-5 text-base-content/30 group-focus-within:text-primary transition-all duration-300"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="digite o nome e aperte enter para buscar..."
                class="w-full pl-12 pr-12 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 focus:outline-none shadow-sm transition-all duration-300 text-base-content placeholder:text-base-content/30 placeholder:text-sm" />
        </div>
    </form>

    <div class="hidden md:flex gap-4 text-right">
        <div class="flex flex-col items-end">
            <span class="text-[10px] font-bold uppercase opacity-40 tracking-widest">Registros</span>
            <span class="text-sm font-semibold text-base-content">{{ $pricings->count() }} categorias ativas</span>
        </div>
    </div>
</div>

{{-- Tabela --}}
{{-- Tabela --}}
<div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table w-full border-separate border-spacing-0">
            <thead>
                <tr class="bg-base-200/50">
                    <th class="text-xs uppercase tracking-wider font-semibold pl-6 h-12 text-base-content/60">Categoria</th>
                    <th class="text-xs uppercase tracking-wider font-semibold h-12 text-base-content/60">Valor Hora</th>
                    <th class="text-xs uppercase tracking-wider font-semibold h-12 text-base-content/60">Mensalidade</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center h-12 text-base-content/60">Vagas</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center h-12 text-base-content/60">Status</th>
                    <th class="text-right text-xs uppercase tracking-wider font-semibold pr-6 h-12 text-base-content/60">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base-300">
                @forelse($pricings as $price)
                <tr class="hover:bg-base-200/40 transition-colors group text-base-content">
                    <td class="pl-6">
                        <div class="flex flex-col">
                            <span class="font-bold text-base">{{ $price->category }}</span>
                            <span class="text-[9px] opacity-40 font-black uppercase tracking-tighter">ID #{{ $price->id }}</span>
                        </div>
                    </td>
                    <td class="text-sm font-semibold">R$ {{ number_format($price->hourly_price, 2, ',', '.') }}</td>
                    <td class="text-sm font-semibold">R$ {{ number_format($price->monthly_price, 2, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-lg bg-base-200 text-[11px] font-bold">
                            {{ $price->total_spots }}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="badge {{ $price->is_active ? 'badge-success' : 'badge-ghost opacity-60' }} badge-sm gap-1 py-3 px-3 border-none font-bold uppercase text-[9px]">
                            @if($price->is_active) <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span> @endif
                            {{ $price->is_active ? 'Ativo' : 'Inativo' }}
                        </div>
                    </td>
                    <td class="text-right pr-6">
                        <div class="flex justify-end gap-2">
                            <button @click="$dispatch('edit-pricing', { 
                                id: {{ $price->id }}, 
                                category: '{{ $price->category }}', 
                                hourly_price: '{{ number_format($price->hourly_price, 2, '', '') }}', 
                                monthly_price: '{{ number_format($price->monthly_price, 2, '', '') }}', 
                                total_spots: '{{ $price->total_spots }}',
                                is_active: {{ $price->is_active ? 1 : 0 }}
                            })"
                                class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group
                                bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white
                                dark:bg-white/5 dark:text-white dark:hover:bg-white dark:hover:text-[#070708]"
                                title="Editar Precificação">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            </button>

                            <form action="{{ route('admin.pricings.destroy', $price->id) }}" method="POST"
                                onsubmit="return confirm('Confirmar exclusão desta precificação?');">
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
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-28">
                        <div class="flex flex-col items-center justify-center text-center">
                            <div class="w-20 h-20 bg-base-200 rounded-full flex items-center justify-center mb-6">
                                <i data-lucide="layers" class="w-10 h-10 opacity-20 text-base-content"></i>
                            </div>
                            <div class="max-w-xs">
                                <h3 class="font-black text-lg text-base-content/50 tracking-tight">
                                    Nenhuma precificação
                                </h3>
                                <p class="text-[12px] text-base-content/30 italic mt-2 leading-relaxed">
                                    Não encontramos categorias ou tarifas cadastradas. <br>
                                    Clique no botão superior para criar uma nova.
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

{{-- MODAL CADASTRAR --}}
<dialog id="new_pricing_modal" class="modal modal-bottom sm:modal-middle"
    x-data="{ 
        hourly: '', monthly: '', spots: '',
        maskMoney(v) {
            if (!v) return '';
            v = v.replace(/\D/g, '');
            v = (v/100).toFixed(2) + '';
            v = v.replace('.', ',');
            v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            return v;
        }
    }">
    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden border border-base-content/5 shadow-2xl">
        <div class="px-8 py-6 border-b border-base-200 flex justify-between items-center">
            <h3 class="text-xl font-bold tracking-tighter">Cadastrar Precificação</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>
        <form action="{{ route('admin.pricings.store') }}" method="POST">
            @csrf
            <div class="p-8 space-y-6">
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Categoria</span></label>
                    <input type="text" name="category" placeholder="Ex: Carros" required class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm" />
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Valor Hora</span></label>
                        <input type="text" name="hourly_price" x-model="hourly" @input="hourly = maskMoney($event.target.value)" placeholder="0,00" required class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Mensalidade</span></label>
                        <input type="text" name="monthly_price" x-model="monthly" @input="monthly = maskMoney($event.target.value)" placeholder="0,00" required class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Vagas</span></label>
                        <input type="text" name="total_spots" x-model="spots" @input="spots = $event.target.value.replace(/\D/g, '')" placeholder="0" required class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm" />
                    </div>
                </div>
            </div>
            <div class="p-8 bg-base-100 border-t border-base-200/60 flex justify-end gap-3">
                <button type="button" onclick="new_pricing_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20">Salvar</button>
            </div>
        </form>
    </div>
</dialog>

<dialog id="edit_pricing_modal" class="modal modal-bottom sm:modal-middle"
    x-data="{ 
        id: null, category: '', hourly: '', monthly: '', spots: '', active: 1,
        maskMoney(v) {
            if (!v) return '';
            v = v.toString().replace(/\D/g, '');
            v = (v/100).toFixed(2) + '';
            v = v.replace('.', ',');
            v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            return v;
        }
    }"
    @edit-pricing.window="
        id = $event.detail.id;
        category = $event.detail.category;
        hourly = maskMoney($event.detail.hourly_price);
        monthly = maskMoney($event.detail.monthly_price);
        spots = $event.detail.total_spots;
        active = $event.detail.is_active;
        $el.showModal();
    ">

    <div class="modal-box p-0 max-w-2xl bg-base-100 rounded-3xl overflow-hidden border border-base-content/5 shadow-2xl">
        <div class="px-8 py-6 border-b border-base-200 flex justify-between items-center">
            <h3 class="text-xl font-bold tracking-tighter">Editar Precificação</h3>
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost opacity-30">✕</button></form>
        </div>

        <form :action="'{{ url('admin/precificacoes') }}/' + id" method="POST">
            @csrf
            @method('PUT')

            <div class="p-8 space-y-6">
                <div class="form-control group">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Categoria</span></label>
                    <input type="text" name="category" x-model="category" required class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm" />
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div class="form-control">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Valor Hora</span></label>
                        <input type="text" name="hourly_price" x-model="hourly" @input="hourly = maskMoney($event.target.value)" required class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Mensalidade</span></label>
                        <input type="text" name="monthly_price" x-model="monthly" @input="monthly = maskMoney($event.target.value)" required class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm" />
                    </div>
                    <div class="form-control">
                        <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Vagas Totais</span></label>
                        <input type="text" name="total_spots" x-model="spots" required class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-sm" />
                    </div>
                </div>

                <div class="form-control">
                    <label class="label py-1 ml-1"><span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Status</span></label>
                    <select name="is_active" x-model="active" class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:outline-none transition-all text-[10px] font-bold uppercase tracking-widest">
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
            </div>

            <div class="p-8 bg-base-100 border-t border-base-200/60 flex justify-end gap-3">
                <button type="button" onclick="edit_pricing_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30">Cancelar</button>
                <button type="submit" class="btn btn-primary px-10 rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-xl shadow-primary/20">Atualizar</button>
            </div>
        </form>
    </div>
</dialog>

@endsection