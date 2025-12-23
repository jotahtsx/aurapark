@extends('components.layouts.app')

@section('title', 'Visão Geral')

@section('content')

<div class="mb-8">
    <h1 class="text-3xl font-bold">Painel de Controle</h1>
    <p class="text-base-content/70 mt-1">Bem-vindo ao seu ambiente administrativo.</p>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">

    <div class="p-6 bg-base-100 border border-base-300 rounded-2xl shadow-sm hover:shadow-md transition group">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-medium text-base-content/70">Capacidade Total</p>
            <div class="p-2 bg-base-200 text-base-content/50 rounded-lg group-hover:bg-primary group-hover:text-primary-content transition-colors">
                <i data-lucide="layers" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h2 class="text-3xl font-bold tracking-tight">128</h2>
            <span class="text-xs font-medium opacity-50 uppercase">Vagas</span>
        </div>
    </div>

    <div class="p-6 bg-base-100 border border-base-300 rounded-2xl shadow-sm hover:shadow-md transition group">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-medium text-base-content/70">Reservas Hoje</p>
            <div class="p-2 bg-base-200 text-base-content/50 rounded-lg group-hover:bg-success group-hover:text-success-content transition-colors">
                <i data-lucide="calendar-check" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h2 class="text-3xl font-bold tracking-tight">32</h2>
            <span class="text-xs font-medium text-success uppercase">Confirmadas</span>
        </div>
    </div>

    <div class="p-6 bg-base-100 border border-base-300 rounded-2xl shadow-sm hover:shadow-md transition group">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-medium text-base-content/70">Clientes Avulsos</p>
            <div class="p-2 bg-base-200 text-base-content/50 rounded-lg group-hover:bg-warning group-hover:text-warning-content transition-colors">
                <i data-lucide="clock" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="flex items-baseline gap-2">
            <h2 class="text-3xl font-bold tracking-tight">45</h2>
            <span class="text-xs font-medium text-warning uppercase">Em Pátio</span>
        </div>
    </div>

    <div class="p-6 bg-base-100 border border-base-300 rounded-2xl shadow-sm hover:shadow-md transition group">
        <div class="flex items-center justify-between mb-2">
            <p class="text-sm font-medium text-base-content/70">Mensalistas</p>
            <div class="p-2 bg-base-200 text-base-content/50 rounded-lg group-hover:bg-info group-hover:text-info-content transition-colors">
                <i data-lucide="shield-check" class="w-5 h-5"></i>
            </div>
        </div>
        <div class="flex flex-col">
            <div class="flex items-baseline gap-2">
                <h2 class="text-3xl font-bold tracking-tight">51</h2>
                <span class="text-xs font-bold text-info uppercase">Ativos</span>
            </div>
            <div class="flex items-center gap-1 mt-1">
                <span class="text-[10px] font-bold text-error uppercase">03 Inativos</span>
                <span class="text-[10px] opacity-40 italic">(pendentes)</span>
            </div>
        </div>
    </div>

</div>

<div class="flex items-center gap-3 mb-8 mt-12">
    <div class="w-2 h-8 bg-primary rounded-full"></div>
    <h2 class="text-2xl font-bold text-base-content">Situação de Vagas (Mapa em Tempo Real)</h2>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-10">

    @php
    $categories = [
    ['nome' => 'Veículo Pequeno', 'cor_base' => 'emerald', 'icon' => 'car'],
    ['nome' => 'Veículo Médio', 'cor_base' => 'lime', 'icon' => 'car-front'],
    ['nome' => 'Veículo Grande', 'cor_base' => 'amber', 'icon' => 'truck'],
    ['nome' => 'Motos', 'cor_base' => 'sky', 'icon' => 'bike'],
    ];
    @endphp

    @foreach($categories as $cat)
    <div class="bg-base-100 border border-base-300 rounded-3xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
                <div class="p-2 rounded-xl bg-{{ $cat['cor_base'] }}-100 text-{{ $cat['cor_base'] }}-800">
                    <i data-lucide="{{ $cat['icon'] }}" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-lg">{{ $cat['nome'] }}</h3>
            </div>
            <div class="flex flex-col items-end">
                <span class="text-[10px] font-black opacity-40 uppercase tracking-widest">Capacidade 30</span>
            </div>
        </div>

        <div class="grid grid-cols-5 md:grid-cols-10 gap-2">
            @for ($i = 1; $i <= 30; $i++)
                @php
                $isBusy=in_array($i, [3, 10, 11, 22, 25]);
                @endphp
                <button
                @if($isBusy) disabled @endif
                class="aspect-square flex flex-col items-center justify-center rounded-xl border-2 text-[10px] font-bold transition-all transform active:scale-95
                        {{ $isBusy 
                            ? 'bg-red-50 border-red-200 text-red-600 cursor-not-allowed shadow-inner' 
                            : 'bg-green-50 border-green-200 text-green-700 hover:bg-green-100 hover:border-green-400 hover:-translate-y-1 shadow-sm' 
                        }}">

                <span class="text-xs">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>

                <div class="mt-0.5">
                    @if($isBusy)
                    <i data-lucide="lock" class="w-2.5 h-2.5 opacity-60"></i>
                    @else
                    <i data-lucide="check" class="w-2.5 h-2.5 opacity-60"></i>
                    @endif
                </div>
                </button>
                @endfor
        </div>

        <div class="mt-6 pt-4 border-t border-base-200 flex gap-4">
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded bg-green-500"></div>
                <span class="text-[10px] font-bold opacity-60">LIVRE</span>
            </div>
            <div class="flex items-center gap-1.5">
                <div class="w-3 h-3 rounded bg-red-500"></div>
                <span class="text-[10px] font-bold opacity-60">OCUPADA</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

@endsection