@extends('layouts.app')
@section('title', '— Painel Admin')

@section('content')
<div class="bg-gradient-to-br from-azul to-azul-dark text-white rounded-2xl px-6 py-6 text-center mb-6 shadow-hero">
    <div class="text-4xl text-amarelo mb-2"><i class="fa-solid fa-key"></i></div>
    <h2 class="text-xl font-bold mb-1">Painel de Controle</h2>
    <p class="opacity-90 text-sm">Bem-vindo, {{ auth()->user()->nome }}</p>
</div>

<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl p-5 text-center shadow-card">
        <div class="text-4xl font-bold leading-none text-texto">{{ $totais['total'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Total de registros</div>
    </div>
    <div class="bg-white rounded-xl p-5 text-center shadow-card">
        <div class="text-4xl font-bold leading-none text-pend-fg">{{ $totais['pendente'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Pendentes</div>
    </div>
    <div class="bg-white rounded-xl p-5 text-center shadow-card">
        <div class="text-4xl font-bold leading-none text-azul">{{ $totais['em_andamento'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Em andamento</div>
    </div>
    <div class="bg-white rounded-xl p-5 text-center shadow-card">
        <div class="text-4xl font-bold leading-none text-verde">{{ $totais['resolvido'] }}</div>
        <div class="text-xs text-gray-500 mt-1">Resolvidos</div>
    </div>
</div>

<div class="flex flex-col gap-3">
    <a href="{{ route('admin.lista') }}" class="block bg-azul text-white py-3.5 rounded-lg font-semibold text-center hover:opacity-90 hover:shadow-md active:scale-[.98] transition">
        <i class="fa-solid fa-list"></i> Visualizar Lista de Registros
    </a>
    <a href="{{ route('admin.mapa') }}" class="block bg-transparent border-2 border-azul text-azul py-3.5 rounded-lg font-semibold text-center hover:bg-azul/5 active:scale-[.98] transition">
        <i class="fa-solid fa-map-location-dot"></i> Visualizar Mapa
    </a>
</div>
@endsection
