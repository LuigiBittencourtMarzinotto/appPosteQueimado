@extends('layouts.app')
@section('title', '— Início')

@section('content')
<div class="bg-gradient-to-br from-azul to-azul-dark text-white rounded-2xl px-7 py-9 text-center mb-6 shadow-hero">
    <div class="text-5xl text-amarelo mb-3"><i class="fa-solid fa-lightbulb"></i></div>
    <h2 class="text-2xl font-bold mb-2">Olá, {{ auth()->user()->nome }}!</h2>
    <p class="text-[.92rem] opacity-90 mb-7 leading-relaxed">Registre problemas de iluminação pública<br>na sua cidade de forma fácil e rápida.</p>
    <div class="flex flex-col sm:flex-row gap-3 sm:justify-center">
        <a href="{{ route('registros.create') }}" class="block sm:flex-1 sm:max-w-[260px] bg-amarelo text-texto py-3 rounded-lg font-semibold hover:opacity-90 hover:shadow-md active:scale-[.98] transition">
            <i class="fa-solid fa-bolt"></i> Registrar Poste Queimado
        </a>
        <a href="{{ route('registros.index') }}" class="block sm:flex-1 sm:max-w-[260px] bg-transparent border-2 border-white text-white py-3 rounded-lg font-semibold hover:bg-white/10 active:scale-[.98] transition">
            Ver problemas registrados
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-card p-5 hover:shadow-card-hover transition">
    <div class="text-base font-semibold mb-3 text-texto"><i class="fa-solid fa-chart-simple text-azul"></i> Seus registros recentes</div>
    @php
        $recentes = auth()->user()->registros()->with('fotos')->latest()->take(3)->get();
    @endphp
    @forelse($recentes as $r)
        <a href="{{ route('registros.show', $r->id) }}" class="block no-underline text-inherit">
            <div class="flex items-start gap-3.5 py-3.5 border-b border-borda last:border-0 cursor-pointer">
                <div class="flex-1">
                    <h4 class="text-[.95rem] font-semibold mb-0.5">{{ $r->titulo }}</h4>
                    <div class="text-xs text-gray-500 mb-1">{{ $r->created_at->format('d/m/Y') }} · {{ $r->endereco_texto }}</div>
                    <p class="text-sm text-gray-600">{{ Str::limit($r->descricao, 60) }}</p>
                </div>
                @include('partials.status-badge', ['status' => $r->status, 'extra' => 'shrink-0'])
            </div>
        </a>
    @empty
        <p class="text-center text-gray-500 py-5">Nenhum registro ainda.<br>Que tal registrar o primeiro?</p>
    @endforelse
</div>
@endsection
