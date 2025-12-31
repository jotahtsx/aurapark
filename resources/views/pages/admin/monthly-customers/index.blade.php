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