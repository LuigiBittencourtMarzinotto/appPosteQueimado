@extends('layouts.app')
@section('title', '— Detalhe do Registro')
@section('maxw', 'max-w-4xl')

@section('content')
@include('partials.page-header', [
    'titulo'    => 'Registro #'.$registro->id,
    'subtitulo' => 'Recebido em '.$registro->created_at->translatedFormat('d \d\e F \d\e Y \à\s H:i'),
    'back'      => route('admin.lista'),
])

{{-- ═══════════ Ação principal: alterar status ═══════════ --}}
<section class="bg-surface rounded-3xl border border-line shadow-card p-5 sm:p-6 mb-4">
    <div class="flex items-center justify-between gap-3 flex-wrap mb-5">
        <h2 class="font-semibold text-ink">Andamento</h2>
        @include('partials.status-badge', ['status' => $registro->status, 'size' => 'md'])
    </div>

    @include('partials.status-stepper', ['status' => $registro->status])

    <form action="{{ route('admin.atualizarStatus', $registro->id) }}" method="POST"
          class="mt-6 pt-5 border-t border-line-soft">
        @csrf @method('PUT')
        <p class="text-[.85rem] font-semibold text-ink mb-3">Alterar status para:</p>
        <div class="grid sm:grid-cols-3 gap-2.5">
            @foreach([
                ['PENDENTE',     'Pendente',     'fa-clock',              'hover:bg-pend-bg hover:text-pend-fg hover:border-pend-dot/50'],
                ['EM_ANDAMENTO', 'Em andamento', 'fa-screwdriver-wrench', 'hover:bg-and-bg  hover:text-and-fg  hover:border-and-dot/50'],
                ['RESOLVIDO',    'Resolvido',    'fa-circle-check',       'hover:bg-res-bg  hover:text-res-fg  hover:border-res-dot/50'],
            ] as [$valor, $rotulo, $icone, $hover])
                @php $atual = $registro->status === $valor; @endphp
                <button type="submit" name="status" value="{{ $valor }}" @disabled($atual)
                        class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-[.88rem] font-semibold border-2 transition
                               {{ $atual
                                    ? 'bg-brand-600 text-white border-brand-600 cursor-default'
                                    : 'bg-white text-ink-soft border-line '.$hover.' active:scale-[.98]' }}">
                    <i class="fa-solid {{ $atual ? 'fa-check' : $icone }} text-[.8rem]"></i>{{ $rotulo }}
                </button>
            @endforeach
        </div>
        <p class="text-[.76rem] text-ink-muted mt-3">
            <i class="fa-solid fa-circle-info"></i>
            Toda mudança fica registrada no histórico com seu nome e a data.
        </p>
    </form>
</section>

<div class="grid lg:grid-cols-[1.4fr_1fr] gap-4 items-start">
    {{-- ═══════════ Dados do problema ═══════════ --}}
    <section class="bg-surface rounded-3xl border border-line shadow-card overflow-hidden">
        <header class="px-5 sm:px-6 pt-5 pb-4 border-b border-line-soft">
            <h2 class="text-lg font-bold text-ink leading-snug">{{ $registro->titulo }}</h2>
        </header>

        <dl class="divide-y divide-line-soft">
            <div class="px-5 sm:px-6 py-4">
                <dt class="text-[.72rem] font-bold uppercase tracking-wider text-ink-muted mb-1.5">
                    <i class="fa-solid fa-location-dot"></i> Endereço
                </dt>
                <dd class="text-[.93rem] text-ink leading-relaxed">{{ $registro->endereco_texto }}</dd>
                <dd class="flex flex-wrap gap-2 mt-3">
                    <button type="button" data-copiar="{{ $registro->endereco_texto }}"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-ink-soft text-[.76rem] font-semibold hover:bg-slate-200 transition">
                        <i class="fa-regular fa-copy"></i> Copiar
                    </button>
                    <a href="https://www.google.com/maps/dir/?api=1&destination={{ $registro->lat }},{{ $registro->lng }}"
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-slate-100 text-ink-soft text-[.76rem] font-semibold hover:bg-slate-200 transition">
                        <i class="fa-solid fa-diamond-turn-right"></i> Traçar rota
                    </a>
                </dd>
            </div>

            <div class="px-5 sm:px-6 py-4">
                <dt class="text-[.72rem] font-bold uppercase tracking-wider text-ink-muted mb-1.5">
                    <i class="fa-solid fa-align-left"></i> Descrição do cidadão
                </dt>
                <dd class="text-[.93rem] text-ink leading-relaxed whitespace-pre-line">{{ $registro->descricao }}</dd>
            </div>

            <div class="px-5 sm:px-6 py-4">
                <dt class="text-[.72rem] font-bold uppercase tracking-wider text-ink-muted mb-2">
                    <i class="fa-solid fa-map-location-dot"></i> Localização
                </dt>
                <dd class="rounded-2xl overflow-hidden border border-line">
                    <div id="mapa-detalhe"></div>
                </dd>
            </div>

            @if($registro->fotos->count())
            <div class="px-5 sm:px-6 py-4">
                <dt class="text-[.72rem] font-bold uppercase tracking-wider text-ink-muted mb-2">
                    <i class="fa-solid fa-camera"></i> Foto enviada
                </dt>
                <dd>
                    @php $urlFoto = asset('storage/'.$registro->fotos->first()->caminho_arquivo); @endphp
                    <button type="button" data-abrir-foto="{{ $urlFoto }}"
                            class="group relative block w-full rounded-2xl overflow-hidden border border-line">
                        <img src="{{ $urlFoto }}" alt="Foto enviada pelo cidadão" loading="lazy"
                             class="w-full max-h-72 object-cover group-hover:scale-[1.02] transition duration-300">
                        <span class="absolute bottom-2.5 right-2.5 inline-flex items-center gap-1.5 bg-black/60 text-white text-[.72rem] font-semibold px-2.5 py-1.5 rounded-lg">
                            <i class="fa-solid fa-expand"></i> Ampliar
                        </span>
                    </button>
                </dd>
            </div>
            @endif
        </dl>
    </section>

    {{-- ═══════════ Coluna lateral ═══════════ --}}
    <div class="space-y-4">
        {{-- Quem registrou --}}
        <section class="bg-surface rounded-3xl border border-line shadow-card p-5">
            <h2 class="text-[.72rem] font-bold uppercase tracking-wider text-ink-muted mb-3.5">
                <i class="fa-solid fa-user"></i> Quem registrou
            </h2>
            @php
                $autor = $registro->usuario;
                $iniciaisAutor = $autor
                    ? collect(explode(' ', trim($autor->nome)))->filter()->take(2)
                        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('')
                    : '?';
            @endphp
            <div class="flex items-center gap-3">
                <span class="w-11 h-11 shrink-0 rounded-full bg-brand-100 text-brand-700 grid place-items-center font-bold text-sm">
                    {{ $iniciaisAutor }}
                </span>
                <div class="min-w-0">
                    <p class="font-semibold text-[.9rem] text-ink truncate">{{ $autor->nome ?? 'Usuário removido' }}</p>
                    @if($autor)
                        <a href="mailto:{{ $autor->email }}" class="text-[.78rem] text-brand-600 hover:underline truncate block">
                            {{ $autor->email }}
                        </a>
                    @endif
                </div>
            </div>
        </section>

        {{-- Histórico --}}
        <section class="bg-surface rounded-3xl border border-line shadow-card p-5">
            <h2 class="text-[.72rem] font-bold uppercase tracking-wider text-ink-muted mb-4">
                <i class="fa-solid fa-clock-rotate-left"></i> Histórico de alterações
            </h2>

            @if($registro->logs->count())
                <ol class="relative pl-6 border-l-2 border-line space-y-4">
                    @foreach($registro->logs->sortByDesc('created_at') as $log)
                        <li class="relative">
                            <span class="absolute -left-[31px] top-1 w-3.5 h-3.5 rounded-full bg-white border-2 border-brand-400" aria-hidden="true"></span>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @include('partials.status-badge', ['status' => $log->status_anterior])
                                <i class="fa-solid fa-arrow-right text-ink-muted text-[.65rem]"></i>
                                @include('partials.status-badge', ['status' => $log->status_novo])
                            </div>
                            <p class="text-[.72rem] text-ink-muted mt-1.5">
                                {{ $log->admin->nome ?? 'Admin' }}
                                <span class="text-line mx-1">·</span>
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </p>
                        </li>
                    @endforeach
                </ol>
            @else
                <p class="text-[.83rem] text-ink-muted leading-relaxed">
                    Nenhuma alteração ainda. Este registro está no status original desde que foi criado.
                </p>
            @endif
        </section>
    </div>
</div>

{{-- ═══════════ Ampliação de foto ═══════════ --}}
<div id="lightbox" hidden
     class="fixed inset-0 z-[60] bg-black/85 backdrop-blur-sm flex items-center justify-center p-4 animate-fade-in"
     role="dialog" aria-modal="true" aria-label="Foto ampliada">
    <button type="button" id="lightbox-fechar" aria-label="Fechar imagem"
            class="absolute top-4 right-4 w-11 h-11 grid place-items-center rounded-xl bg-white/15 text-white hover:bg-white/25 transition">
        <i class="fa-solid fa-xmark text-lg"></i>
    </button>
    <img id="lightbox-img" src="" alt="Foto do registro, ampliada" class="max-w-full max-h-[85vh] rounded-2xl shadow-2xl">
</div>
@endsection

@section('scripts')
<script>
(() => {
    /* Mapa do registro */
    const map = L.map('mapa-detalhe', { scrollWheelZoom: false })
        .setView([{{ $registro->lat }}, {{ $registro->lng }}], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap', maxZoom: 19,
    }).addTo(map);
    L.marker([{{ $registro->lat }}, {{ $registro->lng }}], {
        icon: L.divIcon({ html: '<div class="pin-pulse"></div>', className: '', iconSize: [18, 18], iconAnchor: [9, 9] })
    }).addTo(map);
    setTimeout(() => map.invalidateSize(), 120);

    /* Copiar endereço */
    document.querySelectorAll('[data-copiar]').forEach(btn => {
        btn.addEventListener('click', async () => {
            try {
                await navigator.clipboard.writeText(btn.dataset.copiar);
                const html = btn.innerHTML;
                btn.innerHTML = '<i class="fa-solid fa-check"></i> Copiado';
                btn.classList.add('bg-res-bg', 'text-res-fg');
                setTimeout(() => {
                    btn.innerHTML = html;
                    btn.classList.remove('bg-res-bg', 'text-res-fg');
                }, 1800);
            } catch (_) { /* navegador sem permissão de área de transferência */ }
        });
    });

    /* Ampliar foto */
    const caixa  = document.getElementById('lightbox');
    const imagem = document.getElementById('lightbox-img');
    const fechar = () => { caixa.hidden = true; document.body.style.overflow = ''; };

    document.querySelectorAll('[data-abrir-foto]').forEach(btn => {
        btn.addEventListener('click', () => {
            imagem.src = btn.dataset.abrirFoto;
            caixa.hidden = false;
            document.body.style.overflow = 'hidden';
            document.getElementById('lightbox-fechar').focus();
        });
    });
    document.getElementById('lightbox-fechar').addEventListener('click', fechar);
    caixa.addEventListener('click', e => { if (e.target === caixa) fechar(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape' && !caixa.hidden) fechar(); });
})();
</script>
@endsection
