@extends('layouts.app')
@section('title', '— Mapa de Registros')
@section('maxw', 'max-w-6xl')

@section('content')
@include('partials.page-header', [
    'titulo'    => 'Mapa de registros',
    'subtitulo' => 'Onde estão os problemas de iluminação relatados',
    'back'      => route('admin.dashboard'),
    'acao'      => '<a href="'.route('admin.lista').'"
                       class="inline-flex items-center gap-2 bg-surface border border-line text-ink-soft px-4 py-2.5 rounded-xl text-sm font-semibold shadow-xs hover:border-brand-300 hover:text-brand-700 transition">
                       <i class="fa-solid fa-list-check"></i><span class="hidden sm:inline">Ver em lista</span>
                    </a>',
])

{{-- ═══════════ Filtros do mapa ═══════════ --}}
<div class="flex items-center justify-between gap-3 flex-wrap mb-3">
    <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-1 px-1 pb-0.5" role="group" aria-label="Mostrar ou esconder status no mapa">
        @foreach([
            ['PENDENTE',     'Pendentes',    $contagens['PENDENTE'],     'bg-pend-dot', 'text-pend-fg', 'bg-pend-bg', 'border-pend-dot/40'],
            ['EM_ANDAMENTO', 'Em andamento', $contagens['EM_ANDAMENTO'], 'bg-and-dot',  'text-and-fg',  'bg-and-bg',  'border-and-dot/40'],
            ['RESOLVIDO',    'Resolvidos',   $contagens['RESOLVIDO'],    'bg-res-dot',  'text-res-fg',  'bg-res-bg',  'border-res-dot/40'],
        ] as [$valor, $rotulo, $qtd, $ponto, $cor, $fundo, $borda])
            <button type="button" data-filtro="{{ $valor }}" aria-pressed="true"
                    class="inline-flex items-center gap-2 shrink-0 px-3.5 py-2 rounded-full text-[.8rem] font-semibold border transition
                           {{ $fundo }} {{ $cor }} {{ $borda }}">
                <span class="w-2 h-2 rounded-full {{ $ponto }}" aria-hidden="true"></span>
                {{ $rotulo }}
                <span class="text-[.7rem] font-bold px-1.5 py-px rounded-full bg-white/70">{{ $qtd }}</span>
            </button>
        @endforeach
    </div>

    <div class="flex gap-2 shrink-0">
        <button type="button" id="btn-ajustar"
                class="inline-flex items-center gap-2 bg-surface border border-line text-ink-soft px-3.5 py-2 rounded-xl text-[.8rem] font-semibold hover:border-brand-300 hover:text-brand-700 transition">
            <i class="fa-solid fa-maximize"></i> Enquadrar tudo
        </button>
        <button type="button" id="btn-minha-area"
                class="inline-flex items-center gap-2 bg-surface border border-line text-ink-soft px-3.5 py-2 rounded-xl text-[.8rem] font-semibold hover:border-brand-300 hover:text-brand-700 transition">
            <i class="fa-solid fa-location-crosshairs"></i> Minha área
        </button>
    </div>
</div>

{{-- ═══════════ Mapa ═══════════ --}}
<div class="relative rounded-3xl overflow-hidden border border-line shadow-card">
    <div id="mapa-admin"></div>

    <p id="contador-mapa"
       class="absolute bottom-3 left-3 z-[500] inline-flex items-center gap-2 bg-white/95 backdrop-blur text-ink-soft text-[.76rem] font-semibold px-3 py-2 rounded-lg shadow-xs"
       aria-live="polite">
        <i class="fa-solid fa-map-pin text-brand-600"></i>
        <span data-contador-texto>{{ $registros->count() }} registros no mapa</span>
    </p>
</div>

<p class="text-center text-[.78rem] text-ink-muted mt-3">
    Toque em um marcador para ver o registro · use os filtros acima para focar em um status
</p>

@if($registros->count() === 0)
    <div class="bg-surface rounded-3xl border border-line shadow-card mt-4">
        @include('partials.empty-state', [
            'icone'  => 'fa-map-location-dot',
            'titulo' => 'Nenhum registro para mostrar',
            'texto'  => 'Quando houver registros com localização, eles aparecem neste mapa.',
        ])
    </div>
@endif
@endsection

@section('scripts')
<script>
(() => {
    const CURITIBA = [-25.4290, -49.2671];
    const registros = @json($registros);

    const map = L.map('mapa-admin').setView(CURITIBA, 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap', maxZoom: 19,
    }).addTo(map);
    setTimeout(() => map.invalidateSize(), 120);

    const CORES = { PENDENTE: '#F59E0B', EM_ANDAMENTO: '#2563EB', RESOLVIDO: '#16A34A' };
    const ROTULOS = { PENDENTE: 'Pendente', EM_ANDAMENTO: 'Em andamento', RESOLVIDO: 'Resolvido' };

    /* Uma camada por status, para os filtros ligarem/desligarem em bloco */
    const camadas = {
        PENDENTE:     L.layerGroup().addTo(map),
        EM_ANDAMENTO: L.layerGroup().addTo(map),
        RESOLVIDO:    L.layerGroup().addTo(map),
    };
    const visiveis = new Set(Object.keys(camadas));

    const pontos = [];

    registros.forEach(r => {
        const lat = parseFloat(r.lat), lng = parseFloat(r.lng);
        if (Number.isNaN(lat) || Number.isNaN(lng)) return;

        const cor = CORES[r.status] || '#94A3B8';
        const icone = L.divIcon({
            className: '',
            html: `<div style="width:16px;height:16px;border-radius:50%;background:${cor};
                               border:3px solid #fff;box-shadow:0 2px 6px rgba(16,24,40,.35);"></div>`,
            iconSize: [16, 16], iconAnchor: [8, 8],
        });

        const marcador = L.marker([lat, lng], { icon: icone, title: r.titulo });
        marcador.bindPopup(`
            <div style="min-width:210px;max-width:260px;">
                <strong style="font-size:.92rem;color:#1A2233;display:block;line-height:1.35;">${escapar(r.titulo)}</strong>
                <span style="display:block;color:#7A8699;font-size:.76rem;margin-top:4px;line-height:1.4;">
                    <i class="fa-solid fa-location-dot"></i> ${escapar(r.endereco_texto || '')}
                </span>
                <span style="display:inline-block;margin-top:8px;padding:3px 10px;border-radius:99px;
                             background:${cor}1f;color:${cor};font-size:.72rem;font-weight:700;">
                    ${ROTULOS[r.status] || r.status}
                </span>
                <a href="/admin/registros/${r.id}"
                   style="display:block;margin-top:10px;font-size:.8rem;color:#1565C0;font-weight:600;text-decoration:none;">
                    Ver detalhes e alterar status →
                </a>
            </div>`);

        (camadas[r.status] || camadas.PENDENTE).addLayer(marcador);
        pontos.push([lat, lng]);
    });

    function escapar(texto) {
        const div = document.createElement('div');
        div.textContent = texto ?? '';
        return div.innerHTML;
    }

    /* Enquadra todos os pontos ao abrir */
    const enquadrar = () => {
        const ativos = registros
            .filter(r => visiveis.has(r.status))
            .map(r => [parseFloat(r.lat), parseFloat(r.lng)])
            .filter(([a, b]) => !Number.isNaN(a) && !Number.isNaN(b));
        if (ativos.length === 1) map.setView(ativos[0], 16);
        else if (ativos.length > 1) map.fitBounds(L.latLngBounds(ativos), { padding: [50, 50], maxZoom: 17 });
    };
    if (pontos.length) enquadrar();
    document.getElementById('btn-ajustar').addEventListener('click', enquadrar);

    /* Filtros por status */
    const contador = document.querySelector('[data-contador-texto]');
    const atualizarContador = () => {
        const n = registros.filter(r => visiveis.has(r.status)).length;
        contador.textContent = n === 1 ? '1 registro no mapa' : `${n} registros no mapa`;
    };

    document.querySelectorAll('[data-filtro]').forEach(btn => {
        btn.addEventListener('click', () => {
            const chave = btn.dataset.filtro;
            const ligado = visiveis.has(chave);

            if (ligado) { visiveis.delete(chave); map.removeLayer(camadas[chave]); }
            else        { visiveis.add(chave);    map.addLayer(camadas[chave]); }

            btn.setAttribute('aria-pressed', String(!ligado));
            btn.classList.toggle('opacity-40', ligado);
            btn.classList.toggle('grayscale', ligado);
            atualizarContador();
        });
    });

    /* Centraliza na posição do administrador */
    document.getElementById('btn-minha-area').addEventListener('click', () => {
        if (!navigator.geolocation) return;
        const btn = document.getElementById('btn-minha-area');
        const html = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Localizando...';
        navigator.geolocation.getCurrentPosition(
            pos => { btn.innerHTML = html; map.setView([pos.coords.latitude, pos.coords.longitude], 15); },
            () => { btn.innerHTML = html; },
            { timeout: 10000 }
        );
    });
})();
</script>
@endsection
