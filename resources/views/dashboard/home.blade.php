@extends('components.layouts.app')

@section('title', 'Visão Geral')

@section('content')

    <div class="mb-8">
        <h1 class="text-3xl font-bold">Painel de Controle</h1>
        <p class="text-base-content/70 mt-1">Bem-vindo ao seu ambiente administrativo.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">

        <div class="p-6 bg-base-200 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-sm text-base-content/70">Usuários Ativos</p>
            <h2 class="text-3xl font-bold mt-2">128</h2>
        </div>

                    <div class="p-6 bg-base-200 rounded-xl shadow hover:shadow-lg transition">
                        <p class="text-sm text-base-content/70">Reservas Hoje</p>
                        <h2 class="text-3xl font-bold mt-2">32</h2>
                    </div>

        <div class="p-6 bg-base-200 rounded-xl shadow hover:shadow-lg transition">
            <p class="text-sm text-base-content/70">Notificações</p>
            <h2 class="text-3xl font-bold mt-2">7</h2>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="lg:col-span-2 p-6 bg-base-200 rounded-xl shadow h-64 flex items-center justify-center">
            <span class="text-base-content/50">[ Área para um gráfico futuramente ]</span>
        </div>

        <div class="p-6 bg-base-200 rounded-xl shadow flex flex-col justify-between">
            <h3 class="text-xl font-semibold mb-2">Informações Rápidas</h3>
            <p class="text-base-content/70 mb-4">
                Este painel será o ponto central para visualizar e gerenciar dados importantes da plataforma.
            </p>
            <a href="#"
                class="mt-auto inline-block px-4 py-2 rounded-lg bg-primary text-primary-content font-medium text-center hover:opacity-90 transition">
                Ver detalhes
            </a>
        </div>

    </div>

@endsection
