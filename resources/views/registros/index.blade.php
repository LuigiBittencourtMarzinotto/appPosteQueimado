@extends('layouts.app')
@section('title', '— Meus Registros')

@section('content')
@include('partials.page-header', [
    'titulo'    => 'Meus Registros',
    'subtitulo' => $contagens['total'] === 1
        ? '1 registro no total'
        : $contagens['total'].' registros no total',
    'acao'      => '<a href="'.route('registros.create').'"
                       class="inline-flex items-center gap-2 bg-brand-600 text-white px-4 py-2.5 rounded-xl text-sm font-semibold shadow-xs hover:bg-brand-700 active:scale-[.98] transition">
                       <i class="fa-solid fa-plus"></i><span class="hidden sm:inline">Novo registro</span>
                    </a>',
])

@if($contagens['total'] > 0)
{{-- ═══════════ Busca + filtros ═══════════ --}}
<div class="mb-5 space-y-3">
    <form method="GET" action="{{ route('registros.index') }}" class="relative">
        @if($status)<input type="hidden" name="status" value="{{ $status }}">@endif
        <label for="busca" class="sr-only">Buscar nos seus registros</label>
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
        <input type="search" id="busca" name="busca" value="{{ $busca }}"
               placeholder="Buscar por título, endereço ou descrição..."
               class="w-full pl-11 pr-24 py-3 bg-surface border border-line rounded-xl text-[.9rem] shadow-xs
                      placeholder:text-ink-muted/70 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition">
        <div class="absolute right-2 top-1/2 -translate-y-1/2 flex items-center gap-1">
            @if($busca !== '')
                <a href="{{ route('registros.index', array_filter(['status' => $status])) }}" aria-label="Limpar busca"
                   class="w-8 h-8 grid place-items-center rounded-lg text-ink-muted hover:text-ink hover:bg-slate-100 transition">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
            <button type="submit"
                    class="px-3 h-8 rounded-lg bg-brand-50 text-brand-700 text-[.78rem] font-semibold hover:bg-brand-100 transition">
                Buscar
            </button>
        </div>
    </form>

    {{-- Filtros por status (roláveis no mobile) --}}
    <div class="flex gap-2 overflow-x-auto no-scrollbar -mx-1 px-1 pb-0.5" role="group" aria-label="Filtrar por status">
        @php
            $chipBase = 'inline-flex items-center gap-2 shrink-0 px-3.5 py-2 rounded-full text-[.8rem] font-semibold border transition';
            $chipOn   = 'bg-brand-600 text-white border-brand-600 shadow-xs';
            $chipOff  = 'bg-surface text-ink-soft border-line hover:border-brand-300 hover:text-brand-700';
            $filtros  = [
                [null,           'Todos',        $contagens['total'],        'bg-slate-400'],
                ['PENDENTE',     'Pendentes',    $contagens['PENDENTE'],     'bg-pend-dot'],
                ['EM_ANDAMENTO', 'Em andamento', $contagens['EM_ANDAMENTO'], 'bg-and-dot'],
                ['RESOLVIDO',    'Resolvidos',   $contagens['RESOLVIDO'],    'bg-res-dot'],
            ];
        @endphp
        @foreach($filtros as [$valor, $rotulo, $qtd, $ponto])
            @php $ativo = $status === $valor; @endphp
            <a href="{{ route('registros.index', array_filter(['status' => $valor, 'busca' => $busca ?: null])) }}"
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
</div>
@endif

{{-- ═══════════ Lista ═══════════ --}}
<div class="bg-surface rounded-3xl border border-line shadow-card overflow-hidden">
    @if($registros->count() && ($busca !== '' || $status))
        <p class="px-4 sm:px-5 py-2.5 bg-slate-50 border-b border-line-soft text-[.78rem] text-ink-muted">
            {{ $registros->count() }} {{ $registros->count() === 1 ? 'resultado' : 'resultados' }}
            @if($busca !== '')para <strong class="text-ink-soft">“{{ $busca }}”</strong>@endif
        </p>
    @endif

    @forelse($registros as $r)
        @include('partials.registro-item', ['r' => $r, 'meta' => 'completo'])
    @empty
        @if($contagens['total'] === 0)
            @include('partials.empty-state', [
                'icone'    => 'fa-lightbulb',
                'titulo'   => 'Nenhum registro ainda',
                'texto'    => 'Assim que você registrar um poste queimado, ele aparece aqui com o status atualizado.',
                'ctaUrl'   => route('registros.create'),
                'ctaLabel' => 'Criar primeiro registro',
            ])
        @else
            @include('partials.empty-state', [
                'icone'    => 'fa-magnifying-glass',
                'titulo'   => 'Nada encontrado com esses filtros',
                'texto'    => 'Tente outra palavra na busca ou volte a ver todos os registros.',
                'ctaUrl'   => route('registros.index'),
                'ctaLabel' => 'Ver todos os registros',
                'ctaIcone' => 'fa-rotate-left',
            ])
        @endif
    @endforelse
</div>
@endsection
