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

</div> {{-- FECHA O GRID DE MÉTRICAS --}}

{{-- TÍTULO DA SEÇÃO DE VAGAS --}}
<div class="flex items-center gap-3 mb-8 mt-12">
    <div class="w-2 h-8 bg-primary rounded-full"></div>
    <h2 class="text-2xl font-bold text-base-content">Situação de Vagas (Mapa em Tempo Real)</h2>
</div>

{{-- GRID DE SETORES (MAPA) --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-8 mb-10">

    @php
    $setores = [
        ['nome' => 'Veículo Pequeno', 'cor' => 'bg-lime-100 text-lime-800 border-lime-200'],
        ['nome' => 'Veículo Médio', 'cor' => 'bg-emerald-100 text-emerald-800 border-emerald-200'],
        ['nome' => 'Veículo Grande', 'cor' => 'bg-green-100 text-green-800 border-green-200'],
        ['nome' => 'Moto', 'cor' => 'bg-teal-100 text-teal-800 border-teal-200'],
    ];
    @endphp

    @foreach($setores as $setor)
        <div class="bg-base-100 border border-base-300 rounded-3xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-bold text-lg flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full {{ explode(' ', $setor['cor'])[0] }}"></span>
                    {{ $setor['nome'] }}
                </h3>
                <span class="text-[10px] font-black opacity-40 uppercase tracking-widest">30 Vagas</span>
            </div>

            <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-10 gap-2">
                @for ($i = 1; $i <= 30; $i++)
                    @php
                        $estaOcupada = in_array($i, [5, 10, 18, 25]);
                    @endphp

                    <button class="aspect-square flex flex-col items-center justify-center rounded-xl border text-[10px] font-bold transition-all hover:scale-110 active:scale-95
                        {{ $estaOcupada 
                            ? 'bg-base-300 border-base-400 text-base-content/30 cursor-not-allowed' 
                            : $setor['cor'] . ' hover:shadow-lg shadow-sm border-current opacity-80' 
                        }}">
                        <span class="opacity-50 text-[8px]">VAGA</span>
                        {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                    </button>
                @endfor
            </div>
        </div>
    @endforeach

</div> {{-- FECHA O GRID DE SETORES --}}

@endsection