@props(['type' => 'info', 'message' => null])

@php
    $finalMessage = $message;
    $finalType = $type;

    if (!$finalMessage) {
        foreach (['success', 'error', 'warning', 'info'] as $t) {
            if (session($t)) {
                $finalMessage = session($t);
                $finalType = $t;
            }
        }
    }

    if (!$finalMessage) {
        return;
    }

    $styles = [
        'success' => 'bg-green-600 text-white',
        'error' => 'bg-red-600 text-white',
        'warning' => 'bg-yellow-500 text-gray-900',
        'info' => 'bg-blue-600 text-white',
    ];
    $css = $styles[$finalType] ?? $styles['info'];
@endphp

<div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.outside="show = false"
    x-init="setTimeout(() => show = false, 5000)" {{-- Opcional: Fechar automaticamente após 5s --}}
    class="flash-message fixed top-6 right-6 max-w-sm w-full z-[9999] p-4 rounded-xl shadow-2xl {{ $css }}"
    role="alert">
    <div class="flex justify-between items-center gap-4">
        <span class="font-semibold text-lg">{{ $finalMessage }}</span>
        <button @click="show = false" class="flash-close p-2 rounded-full hover:bg-white/20 transition"
            aria-label="Fechar alerta">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
