@extends('layouts.app')
@section('title', '— Meus Registros')

@section('content')
<div class="flex justify-between items-center mb-5">
    <h2 class="text-lg font-bold">Meus Registros</h2>
    <a href="{{ route('registros.create') }}" class="bg-azul text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 hover:shadow-md transition">+ Novo</a>
</div>

{{-- Filtros de status --}}
<div class="flex gap-2 mb-4 flex-wrap">
    @php
        $base   = 'no-underline px-3.5 py-1.5 rounded-xl text-xs font-semibold transition';
        $on     = 'bg-azul text-white';
        $off    = 'bg-gray-200 text-gray-700 hover:bg-gray-300';
    @endphp
    <a href="{{ route('registros.index') }}" class="{{ $base }} {{ !request('status') ? $on : $off }}">Todos</a>
    <a href="{{ route('registros.index', ['status'=>'PENDENTE']) }}"     class="{{ $base }} {{ request('status')=='PENDENTE'     ? $on : $off }}">Pendente</a>
    <a href="{{ route('registros.index', ['status'=>'EM_ANDAMENTO']) }}" class="{{ $base }} {{ request('status')=='EM_ANDAMENTO' ? $on : $off }}">Em andamento</a>
    <a href="{{ route('registros.index', ['status'=>'RESOLVIDO']) }}"    class="{{ $base }} {{ request('status')=='RESOLVIDO'    ? $on : $off }}">Resolvido</a>
</div>

@php
    $query = auth()->user()->registros()->with('fotos')->latest();
    if (request('status')) $query->where('status', request('status'));
    $registros = $query->get();
@endphp

<div class="bg-white rounded-2xl shadow-card px-5 py-2 hover:shadow-card-hover transition">
    @forelse($registros as $r)
        <a href="{{ route('registros.show', $r->id) }}" class="block no-underline text-inherit">
            <div class="flex items-start gap-3.5 py-3.5 border-b border-borda last:border-0 cursor-pointer">
                @if($r->fotos->count())
                    <img src="{{ asset('storage/'.$r->fotos->first()->caminho_arquivo) }}"
                         class="w-14 h-14 object-cover rounded-lg shrink-0">
                @else
                    <div class="w-14 h-14 bg-gray-100 rounded-lg flex items-center justify-center text-2xl shrink-0 text-pend-fg"><i class="fa-solid fa-lightbulb"></i></div>
                @endif
                <div class="flex-1 min-w-0">
                    <h4 class="text-[.95rem] font-semibold mb-0.5">{{ $r->titulo }}</h4>
                    <div class="text-xs text-gray-500 mb-1">{{ $r->created_at->format('d \d\e F \d\e Y') }}</div>
                    <p class="text-sm text-gray-600">{{ $r->endereco_texto }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ Str::limit($r->descricao, 55) }}</p>
                </div>
                @include('partials.status-badge', ['status' => $r->status, 'extra' => 'shrink-0'])
            </div>
        </a>
    @empty
        <div class="text-center py-10 text-gray-500">
            <div class="text-5xl mb-3 text-pend-fg"><i class="fa-solid fa-lightbulb"></i></div>
            <p>Nenhum registro encontrado.</p>
            <a href="{{ route('registros.create') }}" class="inline-block bg-azul text-white px-5 py-2.5 rounded-lg font-semibold mt-4 hover:opacity-90 hover:shadow-md transition">Criar primeiro registro</a>
        </div>
    @endforelse
</div>
@endsection
