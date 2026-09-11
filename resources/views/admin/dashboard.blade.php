@extends('layouts.app')
@section('title', '— Painel Admin')
@section('maxw', 'max-w-5xl')

@section('content')
@php
    $total     = $contagens['total'];
    $resolvido = $contagens['RESOLVIDO'];
    $taxa      = $total > 0 ? round($resolvido / $total * 100) : 0;
    $pct       = fn ($n) => $total > 0 ? round($n / $total * 100) : 0;
@endphp

{{-- ═══════════ Cabeçalho ═══════════ --}}
<div class="flex flex-wrap items-end justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink">Painel de controle</h1>
        <p class="text-sm text-ink-muted mt-1">
            Visão geral dos registros de iluminação pública · {{ now()->translatedFormat('d \d\e F \d\e Y') }}
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.lista') }}"
           class="inline-flex items-center gap-2 bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-xs hover:bg-brand-700 active:scale-[.98] transition">
            <i class="fa-solid fa-list-check"></i> Gerenciar registros
        </a>
        <a href="{{ route('admin.mapa') }}"
           class="inline-flex items-center gap-2 bg-surface border border-line text-ink-soft px-4 py-2.5 rounded-xl text-sm font-semibold shadow-xs hover:border-brand-300 hover:text-brand-700 transition">
            <i class="fa-solid fa-map-location-dot"></i> Mapa
        </a>
    </div>
</div>

{{-- ═══════════ Indicadores ═══════════ --}}
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4" aria-label="Indicadores gerais">
    @foreach([
        [null,           'Total de registros', 'fa-layer-group',        'text-ink',     'bg-slate-100', 'bg-slate-400'],
        ['PENDENTE',     'Pendentes',          'fa-clock',              'text-pend-fg', 'bg-pend-bg',   'bg-pend-dot'],
        ['EM_ANDAMENTO', 'Em andamento',       'fa-screwdriver-wrench', 'text-and-fg',  'bg-and-bg',    'bg-and-dot'],
        ['RESOLVIDO',    'Resolvidos',         'fa-circle-check',       'text-res-fg',  'bg-res-bg',    'bg-res-dot'],
    ] as [$chave, $rotulo, $icone, $cor, $fundo, $barra])
        @php $valor = $chave ? $contagens[$chave] : $total; @endphp
        <a href="{{ route('admin.lista', array_filter(['status' => $chave])) }}"
           class="bg-surface rounded-2xl border border-line p-4 sm:p-5 shadow-card hover:shadow-card-hover hover:border-brand-200 active:scale-[.99] transition group">
            <div class="flex items-start justify-between gap-2">
                <span class="w-9 h-9 rounded-xl {{ $fundo }} {{ $cor }} grid place-items-center text-sm">
                    <i class="fa-solid {{ $icone }}"></i>
                </span>
                <i class="fa-solid fa-arrow-right text-line text-xs group-hover:text-brand-500 group-hover:translate-x-0.5 transition"></i>
            </div>
            <p class="text-3xl sm:text-4xl font-extrabold leading-none mt-3 {{ $cor }} tabular-nums">{{ $valor }}</p>
            <p class="text-[.76rem] text-ink-muted mt-1.5">{{ $rotulo }}</p>
            @if($chave)
                <div class="mt-3 h-1.5 rounded-full bg-slate-100 overflow-hidden" role="presentation">
                    <div class="h-full rounded-full {{ $barra }}" style="width: {{ $pct($valor) }}%"></div>
                </div>
                <p class="text-[.68rem] text-ink-muted mt-1">{{ $pct($valor) }}% do total</p>
            @endif
        </a>
    @endforeach
</section>

{{-- ═══════════ Taxa de resolução ═══════════ --}}
@if($total > 0)
<section class="bg-surface rounded-3xl border border-line shadow-card p-5 sm:p-6 mb-4">
    <div class="flex items-end justify-between gap-4 mb-3">
        <div>
            <h2 class="font-semibold text-ink">Taxa de resolução</h2>
            <p class="text-[.8rem] text-ink-muted mt-0.5">
                {{ $resolvido }} de {{ $total }} {{ $total === 1 ? 'registro resolvido' : 'registros resolvidos' }}
            </p>
        </div>
        <p class="text-3xl font-extrabold text-res-fg tabular-nums leading-none">{{ $taxa }}%</p>
    </div>

    {{-- Barra empilhada: cada faixa é um status --}}
    <div class="flex h-3 rounded-full overflow-hidden bg-slate-100"
         role="img" aria-label="Distribuição: {{ $pct($contagens['RESOLVIDO']) }}% resolvidos, {{ $pct($contagens['EM_ANDAMENTO']) }}% em andamento, {{ $pct($contagens['PENDENTE']) }}% pendentes">
        <div class="bg-res-dot" style="width: {{ $pct($contagens['RESOLVIDO']) }}%"></div>
        <div class="bg-and-dot" style="width: {{ $pct($contagens['EM_ANDAMENTO']) }}%"></div>
        <div class="bg-pend-dot" style="width: {{ $pct($contagens['PENDENTE']) }}%"></div>
    </div>
    <div class="flex flex-wrap gap-x-5 gap-y-1.5 mt-3 text-[.76rem] text-ink-soft">
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-res-dot"></span> Resolvidos</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-and-dot"></span> Em andamento</span>
        <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-pend-dot"></span> Pendentes</span>
    </div>
</section>
@endif

<div class="grid lg:grid-cols-2 gap-4">
    {{-- ═══════════ Últimos registros recebidos ═══════════ --}}
    <section class="bg-surface rounded-3xl border border-line shadow-card overflow-hidden">
        <header class="flex items-center justify-between gap-3 px-5 py-4 border-b border-line-soft">
            <h2 class="font-semibold text-ink flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-pend-bg text-pend-fg grid place-items-center text-sm">
                    <i class="fa-solid fa-inbox"></i>
                </span>
                Últimos recebidos
            </h2>
            <a href="{{ route('admin.lista') }}" class="text-[.78rem] font-semibold text-brand-600 hover:underline">Ver todos</a>
        </header>

        @forelse($recentes as $r)
            <a href="{{ route('admin.show', $r->id) }}"
               class="group flex items-start gap-3 px-5 py-3.5 border-b border-line-soft last:border-0 hover:bg-brand-50/40 transition">
                <div class="flex-1 min-w-0">
                    <p class="text-[.9rem] font-semibold text-ink truncate group-hover:text-brand-700 transition">{{ $r->titulo }}</p>
                    <p class="text-[.76rem] text-ink-muted mt-0.5 truncate">
                        <i class="fa-solid fa-user text-[.68rem]"></i> {{ $r->usuario->nome ?? 'Usuário removido' }}
                        <span class="text-line mx-1">·</span>
                        {{ $r->created_at->diffForHumans(['short' => true]) }}
                    </p>
                </div>
                @include('partials.status-badge', ['status' => $r->status, 'extra' => 'shrink-0 mt-0.5'])
            </a>
        @empty
            @include('partials.empty-state', [
                'icone'  => 'fa-inbox',
                'titulo' => 'Nenhum registro recebido',
                'texto'  => 'Assim que um cidadão registrar um problema, ele aparece aqui.',
            ])
        @endforelse
    </section>

    {{-- ═══════════ Últimas atualizações da equipe ═══════════ --}}
    <section class="bg-surface rounded-3xl border border-line shadow-card overflow-hidden">
        <header class="px-5 py-4 border-b border-line-soft">
            <h2 class="font-semibold text-ink flex items-center gap-2.5">
                <span class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 grid place-items-center text-sm">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </span>
                Últimas atualizações
            </h2>
        </header>

        @forelse($ultimasAcoes as $log)
            <a href="{{ $log->registro ? route('admin.show', $log->registro->id) : '#' }}"
               class="group block px-5 py-3.5 border-b border-line-soft last:border-0 hover:bg-brand-50/40 transition">
                <p class="text-[.88rem] font-semibold text-ink truncate group-hover:text-brand-700 transition">
                    {{ $log->registro->titulo ?? 'Registro removido' }}
                </p>
                <div class="flex items-center gap-1.5 flex-wrap mt-1.5">
                    @include('partials.status-badge', ['status' => $log->status_anterior])
                    <i class="fa-solid fa-arrow-right text-ink-muted text-[.65rem]"></i>
                    @include('partials.status-badge', ['status' => $log->status_novo])
                </div>
                <p class="text-[.72rem] text-ink-muted mt-1.5">
                    por {{ $log->admin->nome ?? 'Admin' }}
                    <span class="text-line mx-1">·</span>
                    {{ $log->created_at->diffForHumans(['short' => true]) }}
                </p>
            </a>
        @empty
            @include('partials.empty-state', [
                'icone'  => 'fa-clock-rotate-left',
                'titulo' => 'Nenhuma atualização ainda',
                'texto'  => 'Cada mudança de status feita pela equipe fica registrada aqui.',
            ])
        @endforelse
    </section>
</div>
@endsection
