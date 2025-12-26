@extends('components.layouts.app')

@section('title', 'Pagamentos — Gestão de Pagamentos')

@section('content')

<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-bold text-base-content font-black tracking-tight">Gestão de formas de pagamentos</h1>
        <p class="text-base-content/60 text-sm">Gerencie as formas de de pagamento do estacionamento.</p>
    </div>

    <a href="#" onclick="event.preventDefault(); document.getElementById('new_payment_modal').showModal()"
        class="btn btn-primary shadow-lg shadow-primary/20 rounded-xl px-6 font-bold uppercase text-[10px] tracking-widest">
        <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
        Nova forma de pagamento
    </a>
</div>

<div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-10">
    <form action="{{ route('admin.payments.index') }}" method="GET" class="w-full max-w-md group">
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
            <span class="text-sm font-semibold text-base-content">{{ $payments->count() }} formas de pagamentos</span>
        </div>
    </div>
</div>

<div class="card bg-base-100 border border-base-300 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr class="bg-base-200/50">
                    <th class="text-xs uppercase tracking-wider font-semibold pl-6">Forma de pagamento</th>
                    <th class="text-xs uppercase tracking-wider font-semibold text-center">Status</th>
                    <th class="text-right text-xs uppercase tracking-wider font-semibold pr-6">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-base-300">
                @forelse($payments as $payment)
                <tr class="hover:bg-base-200/40 transition-colors group text-base-content">
                    <td class="pl-6 font-bold">{{ $payment->name }}</td>
                    <td class="text-center">
                        @if($payment->is_active)
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
                            {{-- Botão Editar - Ajustado para Payment --}}
                            <button @click="$dispatch('edit-payment', { 
                        id: {{ $payment->id }}, 
                        name: '{{ $payment->name }}', 
                        is_active: {{ $payment->is_active ? 1 : 0 }} 
                    })"
                                class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group
                           bg-blue-600/10 text-blue-600 hover:bg-blue-600 hover:text-white
                           dark:bg-white/5 dark:text-white dark:hover:bg-white dark:hover:text-[#070708]"
                                title="Editar Pagamento">
                                <i data-lucide="edit-3" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                            </button>

                            {{-- Botão Excluir - Rota Corrigida --}}
                            <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST"
                                onsubmit="return confirm('Atenção: Esta ação não pode ser desfeita. Confirmar exclusão?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl transition-all duration-300 cursor-pointer group border-none outline-none
                               bg-red-600/10 text-red-600 hover:bg-red-600 hover:text-white
                               dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white"
                                    title="Excluir Pagamento">
                                    <i data-lucide="trash-2" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="py-20 text-center opacity-30 italic">Nenhuma forma de pagamento cadastrada.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<dialog id="new_payment_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box p-0 max-w-lg bg-base-100 rounded-3xl overflow-hidden border border-base-content/5 shadow-2xl">
        <div class="px-8 py-6 border-b border-base-200 bg-base-100">
            <h3 class="text-xl font-bold tracking-tighter">Cadastrar forma de pagamento</h3>
        </div>
        <form action="{{ route('admin.payments.store') }}" method="POST" class="p-8 space-y-6">
            @csrf

            {{-- Campo Nome --}}
            <div class="form-control group">
                <label class="label py-1 ml-1">
                    <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Nome do Método</span>
                </label>
                <input type="text" name="name" placeholder="Ex: Pix, Cartão de Crédito..." required
                    class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 transition-all text-sm text-base-content" />
            </div>

            {{-- Campo Status (Adicionado agora) --}}
            <div class="form-control group">
                <label class="label py-1 ml-1">
                    <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Status Inicial</span>
                </label>
                <select name="is_active" class="select w-full bg-base-200/50 border-none rounded-2xl h-12 font-bold uppercase text-[10px] px-5 transition-all focus:ring-4 focus:ring-primary/10">
                    <option value="1" selected>🟢 Ativo</option>
                    <option value="0">🔴 Inativo</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-base-200/60 mt-4">
                <button type="button" onclick="new_payment_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30">Cancelar</button>
                <button type="submit" class="px-10 py-4 rounded-2xl font-black bg-primary text-primary-content shadow-xl shadow-primary/20 uppercase text-[10px] tracking-widest">
                    Salvar Método
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10"><button>close</button></form>
</dialog>

<dialog id="edit_payment_modal" class="modal modal-bottom sm:modal-middle"
    x-data="{ id: null, name: '', is_active: 1 }"
    @edit-payment.window="id = $event.detail.id; name = $event.detail.name; is_active = $event.detail.is_active; $el.showModal()">

    <div class="modal-box p-0 max-w-lg bg-base-100 rounded-3xl overflow-hidden border border-base-content/5 shadow-2xl">
        <div class="px-8 py-6 border-b border-base-200 bg-base-100">
            <h3 class="text-xl font-bold tracking-tighter">Editar forma de pagamento</h3>
        </div>

        <form :action="'{{ route('admin.payments.index') }}/' + id" method="POST" class="p-8 space-y-6">
            @csrf
            @method('PUT')

            <div class="form-control group">
                <label class="label py-1 ml-1">
                    <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Nome do Método</span>
                </label>
                <input type="text" name="name" x-model="name" required
                    class="w-full px-5 h-12 bg-base-200/50 border-none rounded-2xl focus:ring-4 focus:ring-primary/10 focus:bg-base-100 transition-all text-sm text-base-content" />
            </div>

            <div class="form-control group">
                <label class="label py-1 ml-1">
                    <span class="label-text font-black text-[9px] uppercase tracking-widest opacity-40">Status</span>
                </label>
                <select name="is_active" x-model="is_active"
                    class="select w-full bg-base-200/50 border-none rounded-2xl h-12 font-bold uppercase text-[10px] px-5 transition-all focus:ring-4 focus:ring-primary/10 text-base-content">
                    <option value="1">🟢 Ativo</option>
                    <option value="0">🔴 Inativo</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-base-200/60 mt-4">
                <button type="button" onclick="edit_payment_modal.close()" class="btn btn-ghost font-bold text-[10px] uppercase tracking-widest opacity-30">Cancelar</button>
                <button type="submit" class="px-10 py-4 rounded-2xl font-black bg-primary text-primary-content shadow-xl shadow-primary/20 uppercase text-[10px] tracking-widest hover:scale-105 transition-transform">
                    Atualizar Dados
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop backdrop-blur-md bg-base-content/10"><button>close</button></form>
</dialog>

@endsection