@extends('layouts.app')
@section('title', '— Registros')
@section('maxw', 'max-w-5xl')

@section('content')
@include('partials.page-header', [
    'titulo'    => 'Registros',
    'subtitulo' => $registros->count() === $contagens['total']
        ? $contagens['total'].' registros no sistema'
        : $registros->count().' de '.$contagens['total'].' registros',
    'back'      => route('admin.dashboard'),
    'acao'      => '<a href="'.route('admin.mapa').'"
                       class="inline-flex items-center gap-2 bg-surface border border-line text-ink-soft px-4 py-2.5 rounded-xl text-sm font-semibold shadow-xs hover:border-brand-300 hover:text-brand-700 transition">
                       <i class="fa-solid fa-map-location-dot"></i><span class="hidden sm:inline">Ver no mapa</span>
                    </a>',
])

{{-- ═══════════ Busca, filtros e ordenação ═══════════ --}}
<div class="mb-5 space-y-3">
    <form method="GET" action="{{ route('admin.lista') }}" class="relative">
        @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
        @if($ordem === 'antigos')<input type="hidden" name="ordem" value="antigos">@endif
        <label for="busca" class="sr-only">Buscar registros</label>
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
        <input type="search" id="busca" name="busca" value="{{ $busca }}"
               placeholder="Buscar por título, endereço, descrição ou cidadão..."
               class="w-full pl-11 pr-24 py-3 bg-surface border border-line rounded-xl text-[.9rem] shadow-xs
                      placeholder:text-ink-muted/70 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition">
        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
            @if($busca !== '')
                <a href="{{ route('admin.lista', array_filter(['status' => $status, 'ordem' => $ordem === 'antigos' ? 'antigos' : null])) }}"
                   aria-label="Limpar busca"
                   class="w-8 h-8 grid place-items-center rounded-lg text-ink-muted hover:text-ink hover:bg-slate-100 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
            <button type="submit" class="px-3 h-8 rounded-lg bg-brand-50 text-brand-700 text-[.78rem] font-semibold hover:bg-brand-100 transition">
                Buscar
            </button>
        </div>
    </form>

    <div class="flex items-center justify-between gap-3 flex-wrap">
        {{-- Filtros por status --}}
        <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-1 px-1 pb-0.5" role="group" aria-label="Filtrar por status">
            @php
                $chipBase = 'inline-flex items-center gap-2 shrink-0 px-3.5 py-2 rounded-full text-[.8rem] font-semibold border transition';
                $chipOn   = 'bg-brand-600 text-white border-brand-600 shadow-xs';
                $chipOff  = 'bg-surface text-ink-soft border-line hover:border-brand-300 hover:text-brand-700';
                $extraQs  = array_filter(['busca' => $busca ?: null, 'ordem' => $ordem === 'antigos' ? 'antigos' : null]);
            @endphp
            @foreach([
                [null,           'Todos',        $contagens['total'],        'bg-slate-400'],
                ['PENDENTE',     'Pendentes',    $contagens['PENDENTE'],     'bg-pend-dot'],
                ['EM_ANDAMENTO', 'Em andamento', $contagens['EM_ANDAMENTO'], 'bg-and-dot'],
                ['RESOLVIDO',    'Resolvidos',   $contagens['RESOLVIDO'],    'bg-res-dot'],
            ] as [$valor, $rotulo, $qtd, $ponto])
                @php $ativo = $status === $valor; @endphp
                <a href="{{ route('admin.lista', array_merge($extraQs, array_filter(['status' => $valor]))) }}"
                   @if($ativo) aria-current="true" @endif
                   class="{{ $chipBase }} {{ $ativo ? $chipOn : $chipOff }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $ativo ? 'bg-white/70' : $ponto }}" aria-hidden="true"></span>
                    {{ $rotulo }}
                    <span class="text-[.7rem] font-bold px-1.5 py-px rounded-full {{ $ativo ? 'bg-white/20' : 'bg-slate-100 text-ink-muted' }}">
                        {{ $qtd }}
                    </span>
                </a>
            @endforeach
        </div>

        {{-- Ordenação --}}
        @php $qsOrdem = array_filter(['status' => $status, 'busca' => $busca ?: null]); @endphp
        <div class="flex items-center gap-1 bg-surface border border-line rounded-xl p-1 shrink-0" role="group" aria-label="Ordenar">
            <a href="{{ route('admin.lista', $qsOrdem) }}"
               class="px-3 py-1.5 rounded-lg text-[.76rem] font-semibold transition
                      {{ $ordem === 'recentes' ? 'bg-brand-50 text-brand-700' : 'text-ink-muted hover:text-ink' }}">
                Mais recentes
            </a>
            <a href="{{ route('admin.lista', array_merge($qsOrdem, ['ordem' => 'antigos'])) }}"
               class="px-3 py-1.5 rounded-lg text-[.76rem] font-semibold transition
                      {{ $ordem === 'antigos' ? 'bg-brand-50 text-brand-700' : 'text-ink-muted hover:text-ink' }}">
                Mais antigos
            </a>
        </div>
    </div>
</div>

{{-- ═══════════ Lista ═══════════ --}}
@forelse($registros as $r)
<article class="bg-surface rounded-2xl border border-line shadow-card hover:shadow-card-hover transition mb-3 overflow-hidden">
    <div class="p-4 sm:p-5">
        <div class="flex items-start gap-4">
            @if($r->fotos->count())
                <button type="button" data-abrir-foto="{{ asset('storage/'.$r->fotos->first()->caminho_arquivo) }}"
                        class="relative shrink-0 group" aria-label="Ampliar foto do registro {{ $r->titulo }}">
                    <img src="{{ asset('storage/'.$r->fotos->first()->caminho_arquivo) }}" alt="" loading="lazy"
                         class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-xl border border-line">
                    <span class="absolute inset-0 rounded-xl bg-black/0 group-hover:bg-black/35 grid place-items-center text-white opacity-0 group-hover:opacity-100 transition">
                        <i class="fa-solid fa-expand"></i>
                    </span>
                </button>
            @else
                <div class="w-20 h-20 sm:w-24 sm:h-24 shrink-0 rounded-xl bg-slate-50 text-line grid place-items-center text-2xl border border-line"
                     title="Sem foto anexada">
                    <i class="fa-solid fa-image"></i>
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <a href="{{ route('admin.show', $r->id) }}"
                       class="text-[.98rem] font-bold text-ink hover:text-brand-700 transition leading-snug">
                        {{ $r->titulo }}
                    </a>
                    @include('partials.status-badge', ['status' => $r->status, 'extra' => 'shrink-0 mt-0.5'])
                </div>

                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-[.76rem] text-ink-muted mt-1.5">
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fa-solid fa-user text-[.68rem]"></i>{{ $r->usuario->nome ?? 'Usuário removido' }}
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <i class="fa-regular fa-clock text-[.68rem]"></i>{{ $r->created_at->format('d/m/Y H:i') }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 font-semibold text-ink-muted">#{{ $r->id }}</span>
                </div>

                <p class="text-[.8rem] text-ink-soft mt-1.5 inline-flex items-start gap-1.5">
                    <i class="fa-solid fa-location-dot text-[.7rem] mt-1 shrink-0"></i>
                    <span>{{ $r->endereco_texto }}</span>
                </p>

                <p class="text-[.83rem] text-ink-muted mt-2 leading-relaxed">{{ Str::limit($r->descricao, 140) }}</p>
            </div>
        </div>
    </div>

    {{-- Alterar status em um clique --}}
    <div class="bg-slate-50 border-t border-line-soft px-4 sm:px-5 py-3 flex items-center justify-between gap-3 flex-wrap">
        <form action="{{ route('admin.atualizarStatus', $r->id) }}" method="POST" class="flex items-center gap-2 flex-wrap">
            @csrf @method('PUT')
            <span class="text-[.72rem] font-bold uppercase tracking-wider text-ink-muted mr-1">Mudar para</span>
            @foreach([
                ['PENDENTE',     'Pendente',     'fa-clock',              'hover:bg-pend-bg hover:text-pend-fg hover:border-pend-dot/40'],
                ['EM_ANDAMENTO', 'Em andamento', 'fa-screwdriver-wrench', 'hover:bg-and-bg  hover:text-and-fg  hover:border-and-dot/40'],
                ['RESOLVIDO',    'Resolvido',    'fa-circle-check',       'hover:bg-res-bg  hover:text-res-fg  hover:border-res-dot/40'],
            ] as [$valor, $rotulo, $icone, $hover])
                @php $atual = $r->status === $valor; @endphp
                <button type="submit" name="status" value="{{ $valor }}" @disabled($atual)
                        title="{{ $atual ? 'Status atual' : 'Marcar como '.$rotulo }}"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[.78rem] font-semibold border transition
                               {{ $atual
                                    ? 'bg-brand-600 text-white border-brand-600 cursor-default'
                                    : 'bg-white text-ink-soft border-line '.$hover }}">
                    <i class="fa-solid {{ $atual ? 'fa-check' : $icone }} text-[.7rem]"></i>{{ $rotulo }}
                </button>
            @endforeach
        </form>

        <a href="{{ route('admin.show', $r->id) }}"
           class="inline-flex items-center gap-1.5 text-[.8rem] font-semibold text-brand-600 hover:underline shrink-0">
            Ver detalhes <i class="fa-solid fa-arrow-right text-[.7rem]"></i>
        </a>
    </div>
</article>
@empty
<div class="bg-surface rounded-3xl border border-line shadow-card">
    @if($contagens['total'] === 0)
        @include('partials.empty-state', [
            'icone'  => 'fa-inbox',
            'titulo' => 'Nenhum registro no sistema',
            'texto'  => 'Assim que um cidadão registrar um problema de iluminação, ele aparece aqui.',
        ])
    @else
        @include('partials.empty-state', [
            'icone'    => 'fa-magnifying-glass',
            'titulo'   => 'Nada encontrado com esses filtros',
            'texto'    => 'Tente outra palavra na busca ou volte a ver todos os registros.',
            'ctaUrl'   => route('admin.lista'),
            'ctaLabel' => 'Ver todos os registros',
            'ctaIcone' => 'fa-rotate-left',
        ])
    @endif
</div>
@endforelse

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
