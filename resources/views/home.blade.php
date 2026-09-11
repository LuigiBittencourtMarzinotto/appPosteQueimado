@extends('layouts.app')
@section('title', '— Início')

@section('content')
@php
    $primeiroNome = explode(' ', trim(auth()->user()->nome))[0];
    $hora = now()->hour;
    $saudacao = $hora < 12 ? 'Bom dia' : ($hora < 18 ? 'Boa tarde' : 'Boa noite');
@endphp

{{-- ═══════════ Ação principal ═══════════ --}}
<section class="relative overflow-hidden bg-gradient-to-br from-brand-600 via-brand-700 to-brand-800 text-white rounded-3xl p-6 sm:p-8 mb-5 shadow-hero animate-fade-up">
    <div class="absolute -top-16 -right-12 w-56 h-56 rounded-full bg-amarelo/10 blur-3xl" aria-hidden="true"></div>

    <div class="relative">
        <p class="text-white/70 text-sm font-medium">{{ $saudacao }},</p>
        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight mt-0.5">{{ $primeiroNome }}!</h1>
        <p class="text-white/75 text-[.92rem] mt-2.5 max-w-sm leading-relaxed">
            Viu um poste apagado? Registre em menos de um minuto e acompanhe o reparo até o fim.
        </p>

        <div class="flex flex-col sm:flex-row gap-2.5 mt-6">
            <a href="{{ route('registros.create') }}"
               class="inline-flex items-center justify-center gap-2.5 bg-amarelo text-brand-900 px-5 py-3.5 rounded-xl font-bold text-[.95rem]
                      hover:brightness-105 active:scale-[.98] shadow-lg shadow-black/10 transition">
                <i class="fa-solid fa-bolt"></i> Registrar poste queimado
            </a>
            <a href="{{ route('registros.index') }}"
               class="inline-flex items-center justify-center gap-2.5 bg-white/10 border border-white/25 text-white px-5 py-3.5 rounded-xl font-semibold text-[.95rem]
                      hover:bg-white/20 active:scale-[.98] transition">
                <i class="fa-solid fa-list"></i> Meus registros
            </a>
        </div>
    </div>
</section>

{{-- ═══════════ Resumo dos meus registros ═══════════ --}}
@if($contagens['total'] > 0)
<section class="mb-5" aria-label="Resumo dos seus registros">
    <div class="grid grid-cols-3 gap-2.5 sm:gap-3">
        @foreach([
            ['PENDENTE',     'Pendentes',    'fa-clock',                 'text-pend-fg', 'bg-pend-bg'],
            ['EM_ANDAMENTO', 'Em andamento', 'fa-screwdriver-wrench',    'text-and-fg',  'bg-and-bg'],
            ['RESOLVIDO',    'Resolvidos',   'fa-circle-check',          'text-res-fg',  'bg-res-bg'],
        ] as [$chave, $rotulo, $icone, $cor, $fundo])
            <a href="{{ route('registros.index', ['status' => $chave]) }}"
               class="bg-surface rounded-2xl border border-line p-3.5 sm:p-4 shadow-card hover:shadow-card-hover hover:border-brand-200 active:scale-[.98] transition">
                <span class="w-8 h-8 rounded-lg {{ $fundo }} {{ $cor }} grid place-items-center text-[.8rem] mb-2">
                    <i class="fa-solid {{ $icone }}"></i>
                </span>
                <span class="block text-2xl font-extrabold leading-none {{ $cor }}">{{ $contagens[$chave] }}</span>
                <span class="block text-[.72rem] text-ink-muted mt-1 leading-tight">{{ $rotulo }}</span>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ═══════════ Registros recentes ═══════════ --}}
<section class="bg-surface rounded-3xl border border-line shadow-card overflow-hidden">
    <header class="flex items-center justify-between gap-3 px-4 sm:px-5 py-4 border-b border-line-soft">
        <h2 class="font-semibold text-ink flex items-center gap-2.5">
            <span class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 grid place-items-center text-sm">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </span>
            Seus últimos registros
        </h2>
        @if($contagens['total'] > 3)
            <a href="{{ route('registros.index') }}" class="text-[.8rem] font-semibold text-brand-600 hover:underline whitespace-nowrap">
                Ver todos ({{ $contagens['total'] }})
            </a>
        @endif
    </header>

    @forelse($recentes as $r)
        @include('partials.registro-item', ['r' => $r])
    @empty
        @include('partials.empty-state', [
            'icone'    => 'fa-lightbulb',
            'titulo'   => 'Você ainda não registrou nada',
            'texto'    => 'Quando encontrar um poste apagado na sua rua, registre aqui — leva menos de um minuto.',
            'ctaUrl'   => route('registros.create'),
            'ctaLabel' => 'Fazer o primeiro registro',
        ])
    @endforelse
</section>

{{-- ═══════════ Como funciona ═══════════ --}}
<section class="mt-5 bg-surface rounded-3xl border border-line shadow-card p-5 sm:p-6">
    <h2 class="font-semibold text-ink mb-4 flex items-center gap-2.5">
        <span class="w-8 h-8 rounded-lg bg-brand-50 text-brand-600 grid place-items-center text-sm">
            <i class="fa-solid fa-circle-info"></i>
        </span>
        Como funciona
    </h2>
    <ol class="space-y-4">
        @foreach([
            ['Você registra',      'Informe o endereço, descreva o problema e, se puder, anexe uma foto.'],
            ['A equipe recebe',    'O registro entra na fila da equipe de iluminação pública como Pendente.'],
            ['Você acompanha',     'O status muda para Em andamento e depois Resolvido — tudo visível aqui.'],
        ] as $i => [$titulo, $texto])
            <li class="flex items-start gap-3.5">
                <span class="w-7 h-7 shrink-0 rounded-full bg-brand-600 text-white grid place-items-center text-[.75rem] font-bold">
                    {{ $i + 1 }}
                </span>
                <span>
                    <span class="block text-[.9rem] font-semibold text-ink">{{ $titulo }}</span>
                    <span class="block text-[.85rem] text-ink-muted leading-snug mt-0.5">{{ $texto }}</span>
                </span>
            </li>
        @endforeach
    </ol>
</section>
@endsection
