@extends('layouts.auth')
@section('title', 'Entrar')

@section('form')
@php
    $input    = 'w-full px-4 py-3 border rounded-xl text-[.95rem] bg-white placeholder:text-ink-muted/70 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition';
    $inputOk  = 'border-line';
    $inputBad = 'border-red-400 bg-red-50/40';
    $label    = 'block text-[.82rem] font-semibold mb-1.5 text-ink-soft';
@endphp

<div class="bg-white rounded-3xl border border-line shadow-card p-7 sm:p-8">
    <h2 class="text-2xl font-bold tracking-tight">Entrar</h2>
    <p class="text-sm text-ink-muted mt-1 mb-6">Acesse sua conta para registrar e acompanhar problemas.</p>

    @if($errors->any())
        <div class="mb-5 rounded-xl bg-red-50 border border-red-200 px-4 py-3 flex items-start gap-2.5" role="alert">
            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 text-sm"></i>
            <div class="text-sm text-red-900 space-y-0.5">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" data-loading novalidate>
        @csrf

        <div class="mb-4">
            <label for="email" class="{{ $label }}">E-mail</label>
            <div class="relative">
                <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
                <input type="email" id="email" name="email" inputmode="email" autocomplete="email"
                       class="{{ $input }} {{ $errors->has('email') ? $inputBad : $inputOk }} pl-11"
                       value="{{ old('email') }}" placeholder="seu@email.com" required autofocus
                       @if($errors->has('email')) aria-invalid="true" @endif>
            </div>
        </div>

        <div class="mb-5">
            <label for="senha" class="{{ $label }}">Senha</label>
            <div class="relative">
                <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
                <input type="password" id="senha" name="senha" autocomplete="current-password"
                       class="{{ $input }} {{ $errors->has('senha') ? $inputBad : $inputOk }} pl-11 pr-12"
                       placeholder="Sua senha" required>
                <button type="button" data-toggle-senha="senha" aria-label="Mostrar senha"
                        class="absolute right-2 top-1/2 -translate-y-1/2 w-9 h-9 grid place-items-center rounded-lg text-ink-muted hover:text-brand-600 hover:bg-brand-50 transition">
                    <i class="fa-solid fa-eye"></i>
                </button>
            </div>
        </div>

        <button type="submit" data-loading-text="Entrando..."
                class="w-full inline-flex items-center justify-center gap-2 bg-brand-600 text-white py-3.5 rounded-xl font-semibold shadow-xs
                       hover:bg-brand-700 active:scale-[.99] disabled:opacity-70 disabled:cursor-wait transition">
            Entrar <i class="fa-solid fa-arrow-right text-[.8rem]"></i>
        </button>
    </form>

    <p class="text-center mt-6 text-sm text-ink-soft">
        Ainda não tem conta?
        <a href="{{ route('cadastro') }}" class="text-brand-600 font-semibold hover:underline">Criar conta grátis</a>
    </p>
</div>

{{-- Contas de demonstração: preenchem o formulário com um clique --}}
<div class="mt-5 rounded-2xl border border-dashed border-line bg-white/60 p-4">
    <p class="text-[.7rem] font-bold uppercase tracking-wider text-ink-muted mb-2.5">
        <i class="fa-solid fa-flask"></i> Contas de teste — clique para preencher
    </p>
    <div class="grid gap-2 sm:grid-cols-2">
        <button type="button" data-conta data-email="joao@email.com" data-senha="user123"
                class="flex items-center gap-3 text-left px-3 py-2.5 rounded-xl bg-white border border-line hover:border-brand-300 hover:bg-brand-50/50 transition">
            <span class="w-8 h-8 shrink-0 rounded-lg bg-res-bg text-res-fg grid place-items-center text-xs">
                <i class="fa-solid fa-user"></i>
            </span>
            <span class="min-w-0">
                <span class="block text-[.8rem] font-semibold">Usuário comum</span>
                <span class="block text-[.7rem] text-ink-muted truncate">joao@email.com</span>
            </span>
        </button>
        <button type="button" data-conta data-email="admin@postequeimado.com" data-senha="admin123"
                class="flex items-center gap-3 text-left px-3 py-2.5 rounded-xl bg-white border border-line hover:border-brand-300 hover:bg-brand-50/50 transition">
            <span class="w-8 h-8 shrink-0 rounded-lg bg-brand-100 text-brand-700 grid place-items-center text-xs">
                <i class="fa-solid fa-shield-halved"></i>
            </span>
            <span class="min-w-0">
                <span class="block text-[.8rem] font-semibold">Administrador</span>
                <span class="block text-[.7rem] text-ink-muted truncate">admin@postequeimado.com</span>
            </span>
        </button>
    </div>
</div>
@endsection
