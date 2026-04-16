@extends('layouts.app')
@section('title', '— Mapa')

@section('content')
<div class="flex justify-between items-center mb-4">
    <div class="flex items-center gap-2.5">
        <a href="{{ route('admin.dashboard') }}" class="text-azul text-2xl no-underline hover:opacity-80 transition">←</a>
        <h2 class="text-lg font-bold">Mapa de Registros</h2>
    </div>
    <a href="{{ route('admin.lista') }}" class="bg-transparent border-2 border-azul text-azul px-3.5 py-1.5 rounded-lg text-sm font-semibold hover:bg-azul/5 transition">
        <i class="fa-solid fa-list"></i> Lista
    </a>
</div>

{{-- Legenda --}}
<div class="flex gap-3 mb-3 flex-wrap text-xs">
    <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-pend-fg"></i> Pendente</span>
    <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-azul"></i> Em andamento</span>
    <span class="flex items-center gap-1.5"><i class="fa-solid fa-circle text-verde"></i> Resolvido</span>
</div>

<div id="mapa-admin" class="rounded-xl border border-borda"></div>

<div class="mt-3 text-gray-500 text-xs text-center">
    {{ $registros->count() }} registro(s) no mapa · Clique nos marcadores para ver detalhes
</div>
@endsection

@section('scripts')
<script>
    const map = L.map('mapa-admin').setView([-25.4290, -49.2671], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);
    setTimeout(() => map.invalidateSize(), 100);

    const cores = {
        PENDENTE:     '#F57F17',
        EM_ANDAMENTO: '#1565C0',
        RESOLVIDO:    '#2E7D32',
    };

    const registros = @json($registros);

    registros.forEach(r => {
        const cor = cores[r.status] || '#999';
        const icon = L.divIcon({
            html: `<div style="background:${cor}; width:14px; height:14px; border-radius:50%; border:2px solid white; box-shadow:0 2px 4px rgba(0,0,0,.3);"></div>`,
            iconSize: [14,14], iconAnchor: [7,7]
        });

        const marker = L.marker([r.lat, r.lng], { icon }).addTo(map);

        const statusLabel = { PENDENTE:'Pendente', EM_ANDAMENTO:'Em andamento', RESOLVIDO:'Resolvido' };
        marker.bindPopup(`
            <div style="min-width:180px; font-family:'Segoe UI', sans-serif;">
                <strong style="font-size:.9rem;">${r.titulo}</strong><br>
                <small style="color:#757575;"><i class="fa-solid fa-location-dot"></i> ${r.endereco_texto}</small><br>
                <span style="display:inline-block; margin-top:6px; padding:2px 8px; border-radius:10px;
                      background:${cor}22; color:${cor}; font-size:.75rem; font-weight:600;">
                    ${statusLabel[r.status]}
                </span><br>
                <a href="/admin/registros/${r.id}" style="font-size:.8rem; color:#1565C0; margin-top:6px; display:inline-block;">
                    Ver detalhes →
                </a>
            </div>
        `);
    });
</script>
@endsection
