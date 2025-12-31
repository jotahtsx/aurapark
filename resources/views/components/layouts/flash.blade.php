@props(['type' => 'info', 'message' => null])

@php
    $finalMessage = $message ?? session($type);
    if (!$finalMessage && !$message) {
        foreach (['success', 'error', 'warning', 'info'] as $t) {
            if (session($t)) { $finalMessage = session($t); $finalType = $t; break; }
        }
    }
    $finalType = $finalType ?? $type;
    if (!$finalMessage) return;

    $styles = [
        'success' => ['icon' => 'check-circle', 'color' => 'text-blue-600', 'border' => 'border-blue-600/20', 'label' => 'Sucesso'],
        'error'   => ['icon' => 'alert-circle', 'color' => 'text-red-500',  'border' => 'border-red-500/20',  'label' => 'Erro'],
    ];
    $style = $styles[$finalType] ?? $styles['success'];
@endphp

<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="translate-x-full opacity-0"
     x-transition:enter-end="translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="pointer-events-auto mb-3">
    
    <div class="relative flex items-center gap-4 p-4 bg-white border {{ $style['border'] }} shadow-[0_15px_35px_rgba(0,0,0,0.06)] rounded-2xl min-w-[320px] overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-1 {{ str_replace('text', 'bg', $style['color']) }}"></div>
        <div class="flex-shrink-0 w-10 h-10 rounded-xl flex items-center justify-center bg-gray-50">
            <i data-lucide="{{ $style['icon'] }}" class="w-5 h-5 {{ $style['color'] }}"></i>
        </div>
        <div class="flex flex-col flex-1">
            <span class="text-[10px] font-black uppercase tracking-widest opacity-40 text-gray-500">{{ $style['label'] }}</span>
            <span class="text-sm font-bold text-gray-800 leading-tight">{{ $finalMessage }}</span>
        </div>
        <button @click="show = false" class="p-1 hover:bg-gray-100 rounded-lg transition-colors">
            <i data-lucide="x" class="w-4 h-4 text-gray-400"></i>
        </button>
    </div>
</div>