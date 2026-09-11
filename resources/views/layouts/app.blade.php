<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1565C0">
    <meta name="description" content="Registre e acompanhe problemas de iluminação pública na sua cidade.">
    <title>Poste Queimado @yield('title')</title>

    @include('partials.head-assets')
</head>
<body class="font-sans bg-canvas text-ink min-h-screen antialiased {{ auth()->check() ? 'pb-nav md:pb-10' : '' }}">

<a href="#conteudo" class="skip-link">Pular para o conteúdo</a>

@auth
@php
    $user    = auth()->user();
    $isAdmin = $user->tipo === 'ADMIN';
    $iniciais = collect(explode(' ', trim($user->nome)))
        ->filter()->take(2)
        ->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))
        ->implode('');

    $menu = $isAdmin ? [
        ['route' => 'admin.dashboard', 'label' => 'Painel',    'icon' => 'fa-gauge-high'],
        ['route' => 'admin.lista',     'label' => 'Registros', 'icon' => 'fa-list-check'],
        ['route' => 'admin.mapa',      'label' => 'Mapa',      'icon' => 'fa-map-location-dot'],
    ] : [
        ['route' => 'home',             'label' => 'Início',    'icon' => 'fa-house'],
        ['route' => 'registros.index',  'label' => 'Registros', 'icon' => 'fa-list'],
        ['route' => 'registros.create', 'label' => 'Registrar', 'icon' => 'fa-circle-plus'],
    ];
@endphp

{{-- ═══════════════════ Barra superior ═══════════════════ --}}
<header class="sticky top-0 z-40 bg-brand-700 text-white shadow-md">
    <nav class="max-w-6xl mx-auto h-16 px-4 sm:px-6 flex items-center justify-between gap-4" aria-label="Navegação principal">

        <a href="{{ $isAdmin ? route('admin.dashboard') : route('home') }}" class="flex items-center gap-2.5 shrink-0 group">
            <span class="w-9 h-9 rounded-xl bg-white/15 grid place-items-center text-amarelo text-lg group-hover:bg-white/25 transition">
                <i class="fa-solid fa-lightbulb"></i>
            </span>
            <span class="leading-tight">
                <span class="block font-bold tracking-tight text-[1.02rem]">Poste Queimado</span>
                <span class="hidden sm:block text-[.68rem] text-white/60 font-medium uppercase tracking-wider">
                    {{ $isAdmin ? 'Painel administrativo' : 'Iluminação pública' }}
                </span>
            </span>
        </a>

        {{-- Links principais (desktop) --}}
        <div class="hidden md:flex items-center gap-1">
            @foreach($menu as $item)
                @php $ativo = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   @if($ativo) aria-current="page" @endif
                   class="flex items-center gap-2 text-sm px-3.5 py-2 rounded-xl font-medium transition
                          {{ $ativo ? 'bg-white text-brand-700 shadow-xs' : 'text-white/85 hover:bg-white/15 hover:text-white' }}">
                    <i class="fa-solid {{ $item['icon'] }} text-[.85rem]"></i>{{ $item['label'] }}
                </a>
            @endforeach
        </div>

        {{-- Menu da conta --}}
        <div class="relative shrink-0" data-menu>
            <button type="button" data-menu-trigger aria-expanded="false" aria-haspopup="true"
                    class="flex items-center gap-2 p-1 md:pr-2.5 rounded-full hover:bg-white/15 transition">
                <span class="w-9 h-9 rounded-full bg-amarelo text-brand-800 grid place-items-center font-bold text-[.8rem] shadow-xs">
                    {{ $iniciais ?: 'U' }}
                </span>
                <span class="hidden md:block text-left leading-tight max-w-[9rem]">
                    <span class="block text-[.82rem] font-semibold truncate">{{ $user->nome }}</span>
                    <span class="block text-[.68rem] text-white/60">{{ $isAdmin ? 'Administrador' : 'Cidadão' }}</span>
                </span>
                <i class="fa-solid fa-chevron-down text-[.6rem] text-white/70 hidden md:block"></i>
            </button>

            <div data-menu-panel hidden
                 class="absolute right-0 mt-2 w-64 bg-white text-ink rounded-2xl shadow-pop overflow-hidden animate-pop-in origin-top-right">
                <div class="px-4 py-3.5 bg-slate-50 border-b border-line">
                    <p class="font-semibold text-sm truncate">{{ $user->nome }}</p>
                    <p class="text-xs text-ink-muted truncate">{{ $user->email }}</p>
                    <span class="inline-flex items-center gap-1.5 mt-2 text-[.68rem] font-semibold px-2 py-0.5 rounded-full
                                 {{ $isAdmin ? 'bg-brand-100 text-brand-700' : 'bg-res-bg text-res-fg' }}">
                        <i class="fa-solid {{ $isAdmin ? 'fa-shield-halved' : 'fa-user' }}"></i>
                        {{ $isAdmin ? 'Administrador' : 'Usuário comum' }}
                    </span>
                </div>
                <div class="p-1.5">
                    @foreach($menu as $item)
                        <a href="{{ route($item['route']) }}"
                           class="md:hidden flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm hover:bg-slate-100 transition">
                            <i class="fa-solid {{ $item['icon'] }} text-ink-muted w-4 text-center"></i>{{ $item['label'] }}
                        </a>
                    @endforeach
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-red-600 font-medium hover:bg-red-50 transition">
                            <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>Sair da conta
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</header>
@endauth

{{-- ═══════════════════ Avisos (toast) ═══════════════════ --}}
<div class="fixed top-20 inset-x-0 z-50 flex flex-col items-center gap-2 px-4 pointer-events-none" role="status" aria-live="polite">
    @if(session('sucesso'))
        <div data-toast class="pointer-events-auto w-full max-w-md flex items-start gap-3 bg-white border-l-4 border-res-dot rounded-xl shadow-pop px-4 py-3 animate-slide-in">
            <i class="fa-solid fa-circle-check text-res-dot mt-0.5"></i>
            <p class="text-sm text-ink flex-1">{{ session('sucesso') }}</p>
            <button type="button" data-toast-close aria-label="Fechar aviso" class="text-ink-muted hover:text-ink transition text-sm leading-none p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif
    @if(session('erro'))
        <div data-toast class="pointer-events-auto w-full max-w-md flex items-start gap-3 bg-white border-l-4 border-red-500 rounded-xl shadow-pop px-4 py-3 animate-slide-in">
            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5"></i>
            <p class="text-sm text-ink flex-1">{{ session('erro') }}</p>
            <button type="button" data-toast-close aria-label="Fechar aviso" class="text-ink-muted hover:text-ink transition text-sm leading-none p-1">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif
</div>

{{-- ═══════════════════ Conteúdo ═══════════════════ --}}
<main id="conteudo" class="px-4 sm:px-6 py-6 sm:py-8 mx-auto @yield('maxw', 'max-w-3xl')">
    @if($errors->any())
        <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 px-4 py-3.5 animate-fade-up" role="alert">
            <div class="flex items-start gap-3">
                <i class="fa-solid fa-triangle-exclamation text-red-500 mt-0.5"></i>
                <div class="text-sm text-red-900">
                    <p class="font-semibold mb-1">
                        {{ $errors->count() === 1 ? 'Corrija o campo abaixo:' : 'Corrija os '.$errors->count().' campos abaixo:' }}
                    </p>
                    <ul class="space-y-0.5 {{ $errors->count() > 1 ? 'list-disc pl-4' : '' }}">
                        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @yield('content')
</main>

@auth
{{-- ═══════════════════ Navegação inferior (mobile) ═══════════════════ --}}
@php
    $bnBase = 'flex flex-col items-center justify-center gap-1 flex-1 h-full text-[.68rem] font-medium transition relative';
    $bnOff  = 'text-ink-muted hover:text-brand-600';
    $bnOn   = 'text-brand-600 before:absolute before:top-0 before:left-1/2 before:-translate-x-1/2 before:w-8 before:h-[3px] before:bg-brand-600 before:rounded-b-full';
@endphp
<nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 backdrop-blur border-t border-line shadow-btm-nav safe-bottom" aria-label="Navegação rápida">
    <div class="flex items-stretch h-[62px] max-w-lg mx-auto px-2">
        @if($isAdmin)
            @foreach($menu as $item)
                @php $ativo = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}" @if($ativo) aria-current="page" @endif class="{{ $bnBase }} {{ $ativo ? $bnOn : $bnOff }}">
                    <i class="fa-solid {{ $item['icon'] }} text-base"></i>{{ $item['label'] }}
                </a>
            @endforeach
            <button type="button" data-menu-trigger-mobile class="{{ $bnBase }} {{ $bnOff }}">
                <i class="fa-solid fa-user text-base"></i>Conta
            </button>
        @else
            @php
                $aHome = request()->routeIs('home');
                $aList = request()->routeIs('registros.index') || request()->routeIs('registros.show');
            @endphp
            <a href="{{ route('home') }}" @if($aHome) aria-current="page" @endif class="{{ $bnBase }} {{ $aHome ? $bnOn : $bnOff }}">
                <i class="fa-solid fa-house text-base"></i>Início
            </a>
            <a href="{{ route('registros.index') }}" @if($aList) aria-current="page" @endif class="{{ $bnBase }} {{ $aList ? $bnOn : $bnOff }}">
                <i class="fa-solid fa-list text-base"></i>Registros
            </a>
            <div class="flex-1 flex justify-center items-start">
                <a href="{{ route('registros.create') }}" aria-label="Registrar novo problema"
                   class="w-14 h-14 -mt-6 rounded-2xl bg-brand-600 text-white grid place-items-center text-xl shadow-hero
                          hover:bg-brand-700 active:scale-95 transition ring-4 ring-canvas">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>
            <button type="button" data-menu-trigger-mobile class="{{ $bnBase }} {{ $bnOff }}">
                <i class="fa-solid fa-user text-base"></i>Conta
            </button>
        @endif
    </div>
</nav>
@endauth

{{-- ═══════════════════ Comportamentos de UI ═══════════════════ --}}
<script>
(() => {
    /* Toasts: fecham no clique e desaparecem sozinhos em 5s */
    document.querySelectorAll('[data-toast]').forEach(toast => {
        const fechar = () => {
            toast.style.transition = 'opacity .2s, transform .2s';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
            setTimeout(() => toast.remove(), 220);
        };
        toast.querySelector('[data-toast-close]')?.addEventListener('click', fechar);
        setTimeout(fechar, 5000);
    });

    /* Menu da conta: alterna, fecha ao clicar fora ou com Esc */
    const wrap    = document.querySelector('[data-menu]');
    const trigger = document.querySelector('[data-menu-trigger]');
    const panel   = document.querySelector('[data-menu-panel]');
    if (wrap && trigger && panel) {
        const alternar = (aberto) => {
            panel.hidden = !aberto;
            trigger.setAttribute('aria-expanded', String(aberto));
        };
        trigger.addEventListener('click', e => { e.stopPropagation(); alternar(panel.hidden); });
        document.querySelector('[data-menu-trigger-mobile]')
            ?.addEventListener('click', e => { e.stopPropagation(); alternar(panel.hidden); });
        document.addEventListener('click', e => { if (!wrap.contains(e.target)) alternar(false); });
        document.addEventListener('keydown', e => { if (e.key === 'Escape') alternar(false); });
    }

    /* Formulários marcados com data-loading: evitam duplo envio e mostram progresso */
    document.querySelectorAll('form[data-loading]').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('[type=submit]');
            if (!btn || btn.dataset.busy) return;
            btn.dataset.busy = '1';
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (btn.dataset.loadingText || 'Enviando...');
            setTimeout(() => { btn.disabled = true; }, 0);
        });
    });
})();
</script>

@yield('scripts')
</body>
</html>
