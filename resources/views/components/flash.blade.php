@props(['type' => 'info', 'message'])

@php
    $colors = [
        'success' => 'bg-green-600 text-white',
        'error' => 'bg-red-600 text-white',
        'warning' => 'bg-yellow-500 text-gray-900',
        'info' => 'bg-blue-600 text-white',
    ];
    $classes = $colors[$type] ?? $colors['info'];
@endphp

<div x-data="{ show: true }" x-show="show" x-cloak x-init="setTimeout(() => show = false, 10000)" @click.away="show = false"
    x-transition:enter="transform ease-out duration-300" x-transition:enter-start="translate-x-full opacity-0"
    x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transform ease-in duration-300"
    x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0"
    class="fixed top-5 right-5 z-50 w-full max-w-sm sm:max-w-md p-6 rounded-xl shadow-2xl {{ $classes }}"
    role="alert">
    <div class="flex items-center justify-between">
        <p class="text-lg font-semibold pr-4">{{ $message }}</p>
        <button @click="show = false"
            class="ml-4 flex-shrink-0 p-2 rounded-full hover:bg-white/20 transition-colors duration-200 cursor-pointer"
            aria-label="Fechar alerta">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
