@extends('layouts.auth')
@section('title', 'Criar conta')

@section('form')
@php
    $input    = 'w-full px-4 py-3 border rounded-xl text-[.95rem] bg-white placeholder:text-ink-muted/70 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition';
    $inputOk  = 'border-line';
    $inputBad = 'border-red-400 bg-red-50/40';
    $label    = 'block text-[.82rem] font-semibold mb-1.5 text-ink-soft';
@endphp

<div class="bg-white rounded-3xl border border-line shadow-card p-7 sm:p-8">
    <h2 class="text-2xl font-bold tracking-tight">Criar conta</h2>
    <p class="text-sm text-ink-muted mt-1 mb-6">Leva menos de um minuto. É grátis.</p>

    @if($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex items-start gap-2.5" role="alert">
            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 text-sm"></i>
            <div class="text-sm text-red-900 space-y-0.5">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('cadastro') }}" data-loading novalidate>
        @csrf

        <div class="mb-4">
            <label for="nome" class="{{ $label }}">Nome completo</label>
            <div class="relative">
                <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
                <input type="text" id="nome" name="nome" autocomplete="name"
                       class="{{ $input }} {{ $errors->has('nome') ? $inputBad : $inputOk }} pl-11"
                       value="{{ old('nome') }}" placeholder="Ex: João Silva" minlength="3" required>
            </div>
        </div>

        <div class="mb-4">
            <label for="email" class="{{ $label }}">E-mail</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
                <input type="email" id="email" name="email" inputmode="email" autocomplete="email"
                       class="{{ $input }} {{ $errors->has('email') ? $inputBad : $inputOk }} pl-11"
                       value="{{ old('email') }}" placeholder="seu@email.com" required>
            </div>
        </div>

        <div class="mb-4">
            <label for="senha" class="{{ $label }}">Senha</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
                <input type="password" id="senha" name="senha" autocomplete="new-password"
                       class="{{ $input }} {{ $errors->has('senha') ? $inputBad : $inputOk }} pl-11 pr-12"
                       placeholder="Mínimo 6 caracteres" minlength="6" required
                       aria-describedby="forca-senha">
                <button type="button" data-toggle-senha="senha" aria-label="Mostrar senha"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 grid place-items-center rounded-lg text-ink-muted hover:text-brand-600 hover:bg-brand-50 transition">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>

            {{-- Medidor de força --}}
            <div id="forca-senha" class="mt-2 hidden" aria-live="polite">
                <div class="flex gap-1.5" aria-hidden="true">
                    <span data-barra class="h-1.5 flex-1 rounded-full bg-line transition-colors"></span>
                    <span data-barra class="h-1.5 flex-1 rounded-full bg-line transition-colors"></span>
                    <span data-barra class="h-1.5 flex-1 rounded-full bg-line transition-colors"></span>
                    <span data-barra class="h-1.5 flex-1 rounded-full bg-line transition-colors"></span>
                </div>
                <p data-forca-texto class="text-xs text-ink-muted mt-1.5"></p>
            </div>
        </div>

        <div class="mb-6">
            <label for="senha_confirmation" class="{{ $label }}">Confirmar senha</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
                <input type="password" id="senha_confirmation" name="senha_confirmation" autocomplete="new-password"
                       class="{{ $input }} {{ $inputOk }} pl-11 pr-12" placeholder="Repita a senha" required
                       aria-describedby="conferencia">
                <button type="button" data-toggle-senha="senha_confirmation" aria-label="Mostrar senha"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 grid place-items-center rounded-lg text-ink-muted hover:text-brand-600 hover:bg-brand-50 transition">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
            <p id="conferencia" class="text-xs mt-1.5 hidden" aria-live="polite"></p>
        </div>

        <button type="submit" data-loading-text="Criando conta..."
                class="w-full inline-flex items-center justify-center gap-2 bg-brand-600 text-white py-3.5 rounded-xl font-semibold shadow-xs
                       hover:bg-brand-700 active:scale-[.99] disabled:opacity-70 disabled:cursor-wait transition">
            Criar conta <i class="fa-solid fa-arrow-right text-[.8rem]"></i>
        </button>
    </form>

    <p class="text-center mt-6 text-sm text-ink-soft">
        Já tem conta?
        <a href="{{ route('login') }}" class="text-brand-600 font-semibold hover:underline">Entrar</a>
    </p>
</div>
@endsection

@section('scripts')
<script>
(() => {
    const senha    = document.getElementById('senha');
    const confirma = document.getElementById('senha_confirmation');
    const medidor  = document.getElementById('forca-senha');
    const barras   = medidor.querySelectorAll('[data-barra]');
    const texto    = medidor.querySelector('[data-forca-texto]');
    const aviso    = document.getElementById('conferencia');

    /* Força = comprimento + variedade de caracteres (0 a 4) */
    const calcularForca = (v) => {
        let pontos = 0;
        if (v.length >= 6)  pontos++;
        if (v.length >= 10) pontos++;
        if (/[A-Z]/.test(v) && /[a-z]/.test(v)) pontos++;
        if (/\d/.test(v) && /[^A-Za-z0-9]/.test(v)) pontos++;
        return Math.min(pontos, 4);
    };

    const niveis = [
        { cor: 'bg-red-400',    rotulo: 'Muito fraca — use pelo menos 6 caracteres', texto: 'text-red-600' },
        { cor: 'bg-red-400',    rotulo: 'Fraca — misture letras e números',           texto: 'text-red-600' },
        { cor: 'bg-pend-dot',   rotulo: 'Média — pode melhorar',                      texto: 'text-pend-fg' },
        { cor: 'bg-brand-500',  rotulo: 'Boa senha',                                  texto: 'text-brand-600' },
        { cor: 'bg-res-dot',    rotulo: 'Senha forte',                                texto: 'text-res-fg' },
    ];

    senha.addEventListener('input', () => {
        const v = senha.value;
        medidor.classList.toggle('hidden', v.length === 0);
        if (!v.length) return;

        const forca = calcularForca(v);
        const nivel = niveis[forca];
        barras.forEach((b, i) => {
            b.className = 'h-1.5 flex-1 rounded-full transition-colors ' + (i < Math.max(forca, 1) ? nivel.cor : 'bg-line');
        });
        texto.textContent = nivel.rotulo;
        texto.className = 'text-xs mt-1.5 font-medium ' + nivel.texto;
        conferir();
    });

    /* Conferência das duas senhas em tempo real */
    const conferir = () => {
        if (!confirma.value.length) { aviso.classList.add('hidden'); return; }
        const igual = senha.value === confirma.value;
        aviso.classList.remove('hidden');
        aviso.innerHTML = igual
            ? '<i class="fa-solid fa-circle-check"></i> As senhas coincidem'
            : '<i class="fa-solid fa-circle-exclamation"></i> As senhas não coincidem';
        aviso.className = 'text-xs mt-1.5 font-medium ' + (igual ? 'text-res-fg' : 'text-red-600');
        confirma.setAttribute('aria-invalid', String(!igual));
    };
    confirma.addEventListener('input', conferir);
})();
</script>
@endsection
