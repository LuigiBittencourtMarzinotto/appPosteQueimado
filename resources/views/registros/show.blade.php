@extends('layouts.app')
@section('title', '— Detalhes')

@section('content')
<div class="flex items-center gap-2.5 mb-5">
    <a href="{{ route('registros.index') }}" class="text-azul text-2xl no-underline hover:opacity-80 transition">←</a>
    <h2 class="text-lg font-bold">Detalhes do Registro</h2>
</div>

<div class="bg-white rounded-2xl shadow-card p-5 mb-4 hover:shadow-card-hover transition">
    <div class="flex justify-between items-start mb-3">
        <h3 class="text-base font-bold flex-1">{{ $registro->titulo }}</h3>
        @include('partials.status-badge', ['status' => $registro->status])
    </div>

    <div class="text-xs text-gray-500 mb-4">
        <i class="fa-solid fa-calendar"></i> {{ $registro->created_at->format('d/m/Y \à\s H:i') }}
    </div>

    <div class="mb-4">
        <label class="block text-xs text-gray-500 mb-1"><i class="fa-solid fa-location-dot"></i> Endereço</label>
        <p class="text-[.95rem]">{{ $registro->endereco_texto }}</p>
    </div>

    <div class="mb-4">
        <label class="block text-xs text-gray-500 mb-1"><i class="fa-solid fa-pen-to-square"></i> Descrição</label>
        <p class="text-[.95rem] leading-relaxed">{{ $registro->descricao }}</p>
    </div>

    <div class="mb-4">
        <label class="block text-xs text-gray-500 mb-1.5"><i class="fa-solid fa-map-location-dot"></i> Localização</label>
        <div id="mapa-detalhe" class="rounded-lg border border-borda mt-1.5"></div>
    </div>

    @if($registro->fotos->count())
        <div class="mb-2">
            <label class="block text-xs text-gray-500 mb-1.5"><i class="fa-solid fa-camera"></i> Foto</label>
            <img src="{{ asset('storage/'.$registro->fotos->first()->caminho_arquivo) }}"
                 class="w-full max-h-56 object-cover rounded-lg mt-1.5">
        </div>
    @endif
</div>

@if($registro->logs->count())
<div class="bg-white rounded-2xl shadow-card p-5 hover:shadow-card-hover transition">
    <div class="text-base font-semibold mb-3 text-texto"><i class="fa-solid fa-clock-rotate-left text-azul"></i> Histórico de status</div>
    @foreach($registro->logs->sortByDesc('created_at') as $log)
        <div class="flex items-center gap-2 py-2 border-b border-gray-100 last:border-0 text-sm">
            @include('partials.status-badge', ['status' => $log->status_anterior])
            <span class="text-gray-400">→</span>
            @include('partials.status-badge', ['status' => $log->status_novo])
            <span class="text-gray-500 ml-auto">{{ $log->created_at->format('d/m/Y H:i') }}</span>
        </div>
    @endforeach
</div>
@endif
@endsection

@section('scripts')
<script>
    const map = L.map('mapa-detalhe').setView([{{ $registro->lat }}, {{ $registro->lng }}], 16);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([{{ $registro->lat }}, {{ $registro->lng }}])
        .addTo(map)
        .bindPopup('{{ addslashes($registro->titulo) }}')
        .openPopup();
    setTimeout(() => map.invalidateSize(), 100);
</script>
@endsection
