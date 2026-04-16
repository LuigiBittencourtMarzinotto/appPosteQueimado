@extends('layouts.app')
@section('title', '— Lista de Registros')

@section('content')
<div class="flex justify-between items-center mb-5">
    <div class="flex items-center gap-2.5">
        <a href="{{ route('admin.dashboard') }}" class="text-azul text-2xl no-underline hover:opacity-80 transition">←</a>
        <h2 class="text-lg font-bold">Todos os Registros</h2>
    </div>
    <a href="{{ route('admin.mapa') }}" class="bg-transparent border-2 border-azul text-azul px-3.5 py-1.5 rounded-lg text-sm font-semibold hover:bg-azul/5 transition">
        <i class="fa-solid fa-map-location-dot"></i> Mapa
    </a>
</div>

{{-- Filtros --}}
<div class="flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('admin.lista') }}"
       class="no-underline px-3.5 py-1.5 rounded-xl text-xs font-semibold transition
              {{ !request('status') ? 'bg-azul text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
       Todos ({{ $registros->count() }})
    </a>
    @foreach(['PENDENTE'=>'Pendente','EM_ANDAMENTO'=>'Em andamento','RESOLVIDO'=>'Resolvido'] as $val => $label)
    <a href="{{ route('admin.lista', ['status'=>$val]) }}"
       class="no-underline px-3.5 py-1.5 rounded-xl text-xs font-semibold transition
              {{ request('status')==$val ? 'bg-azul text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
       {{ $label }}
    </a>
    @endforeach
</div>

@php
    $lista = request('status') ? $registros->where('status', request('status')) : $registros;
@endphp

@forelse($lista as $r)
<div class="bg-white rounded-xl p-4 mb-3 shadow-card hover:shadow-card-hover transition">
    <div class="flex justify-between items-start mb-2">
        <div class="flex-1 min-w-0">
            <h4 class="text-[.95rem] font-semibold mb-1">{{ $r->titulo }}</h4>
            <div class="text-xs text-gray-500 mb-2 flex flex-wrap gap-x-2 gap-y-1">
                <span><i class="fa-solid fa-user"></i> {{ $r->usuario->nome ?? 'N/A' }}</span>
                <span class="text-gray-300">·</span>
                <span><i class="fa-solid fa-location-dot"></i> {{ $r->endereco_texto }}</span>
                <span class="text-gray-300">·</span>
                <span><i class="fa-solid fa-calendar"></i> {{ $r->created_at->format('d/m/Y') }}</span>
            </div>
            <p class="text-sm text-gray-600">{{ Str::limit($r->descricao, 80) }}</p>
        </div>
        @include('partials.status-badge', ['status' => $r->status, 'extra' => 'shrink-0 ml-2.5'])
    </div>

    @if($r->fotos->count())
        <img src="{{ asset('storage/'.$r->fotos->first()->caminho_arquivo) }}"
             class="w-full max-h-40 object-cover rounded-lg mb-2.5">
    @endif

    <form action="{{ route('admin.atualizarStatus', $r->id) }}" method="POST" class="flex gap-2 items-center flex-wrap mt-2.5">
        @csrf @method('PUT')
        <select name="status" class="flex-1 min-w-[140px] px-2.5 py-2 border border-borda rounded-lg text-sm bg-white focus:outline-none focus:border-azul focus:ring-2 focus:ring-azul/20">
            <option value="PENDENTE"     {{ $r->status=='PENDENTE'     ? 'selected':'' }}>Pendente</option>
            <option value="EM_ANDAMENTO" {{ $r->status=='EM_ANDAMENTO' ? 'selected':'' }}>Em andamento</option>
            <option value="RESOLVIDO"    {{ $r->status=='RESOLVIDO'    ? 'selected':'' }}>Resolvido</option>
        </select>
        <button type="submit" class="bg-azul text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition">Atualizar</button>
        <a href="{{ route('admin.show', $r->id) }}" class="bg-transparent border-2 border-azul text-azul px-4 py-2 rounded-lg text-sm font-semibold hover:bg-azul/5 transition">Detalhes</a>
    </form>
</div>
@empty
    <div class="text-center py-10 text-gray-500">
        <p>Nenhum registro encontrado.</p>
    </div>
@endforelse
@endsection
