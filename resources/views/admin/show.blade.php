@extends('layouts.app')
@section('title', '— Detalhe Registro')

@section('content')
<div class="flex items-center gap-2.5 mb-5">
    <a href="{{ route('admin.lista') }}" class="text-azul text-2xl no-underline hover:opacity-80 transition">←</a>
    <h2 class="text-lg font-bold">Detalhe do Registro</h2>
</div>

<div class="bg-white rounded-2xl shadow-card p-5 mb-4 hover:shadow-card-hover transition">
    <div class="flex justify-between items-start mb-3">
        <h3 class="text-base font-bold flex-1">{{ $registro->titulo }}</h3>
        @include('partials.status-badge', ['status' => $registro->status])
    </div>

    <div class="text-xs text-gray-500 mb-4 flex flex-col gap-1">
        <span><i class="fa-solid fa-user"></i> {{ $registro->usuario->nome ?? 'N/A' }} ({{ $registro->usuario->email ?? '' }})</span>
        <span><i class="fa-solid fa-calendar"></i> {{ $registro->created_at->format('d/m/Y \à\s H:i') }}</span>
        <span><i class="fa-solid fa-location-dot"></i> {{ $registro->endereco_texto }}</span>
    </div>

    <div class="mb-4">
        <label class="block text-xs text-gray-500 mb-1">Descrição</label>
        <p class="text-[.95rem] leading-relaxed">{{ $registro->descricao }}</p>
    </div>

    <div class="mb-4">
        <label class="block text-xs text-gray-500 mb-1.5">Localização</label>
        <div id="mapa-detalhe" class="rounded-lg border border-borda mt-1.5"></div>
    </div>

    @if($registro->fotos->count())
        <div class="mb-4">
            <label class="block text-xs text-gray-500 mb-1.5">Foto</label>
            <img src="{{ asset('storage/'.$registro->fotos->first()->caminho_arquivo) }}" class="w-full max-h-56 object-cover rounded-lg">
        </div>
    @endif

    {{-- Atualizar status --}}
    <div class="border-t border-gray-100 pt-4 mt-2">
        <label class="block text-sm font-semibold mb-2">Atualizar Status</label>
        <form action="{{ route('admin.atualizarStatus', $registro->id) }}" method="POST" class="flex gap-2.5 flex-wrap">
            @csrf @method('PUT')
            <select name="status" class="flex-1 min-w-[160px] px-3.5 py-2.5 border border-borda rounded-lg text-[.95rem] bg-white focus:outline-none focus:border-azul focus:ring-2 focus:ring-azul/20">
                <option value="PENDENTE"     {{ $registro->status=='PENDENTE'     ? 'selected':'' }}>Pendente</option>
                <option value="EM_ANDAMENTO" {{ $registro->status=='EM_ANDAMENTO' ? 'selected':'' }}>Em andamento</option>
                <option value="RESOLVIDO"    {{ $registro->status=='RESOLVIDO'    ? 'selected':'' }}>Resolvido</option>
            </select>
            <button type="submit" class="bg-azul text-white px-6 py-2.5 rounded-lg font-semibold hover:opacity-90 hover:shadow-md active:scale-[.98] transition">Salvar</button>
        </form>
    </div>
</div>

@if($registro->logs->count())
<div class="bg-white rounded-2xl shadow-card p-5 hover:shadow-card-hover transition">
    <div class="text-base font-semibold mb-3 text-texto"><i class="fa-solid fa-clock-rotate-left text-azul"></i> Histórico de alterações</div>
    @foreach($registro->logs->sortByDesc('created_at') as $log)
        <div class="flex items-center gap-2 py-2.5 border-b border-gray-100 last:border-0 text-[.83rem] flex-wrap">
            @include('partials.status-badge', ['status' => $log->status_anterior])
            <span class="text-gray-400">→</span>
            @include('partials.status-badge', ['status' => $log->status_novo])
            <span class="text-gray-500">por {{ $log->admin->nome ?? 'Admin' }}</span>
            <span class="text-gray-400 ml-auto">{{ $log->created_at->format('d/m/Y H:i') }}</span>
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
