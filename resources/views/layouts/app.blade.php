<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Poste Queimado @yield('title')</title>

    @include('partials.head-assets')
</head>
<body class="font-sans bg-gray-100 text-texto min-h-screen pb-20">

@auth
<nav class="bg-azul text-white flex items-center justify-between px-6 h-[60px] shadow-md sticky top-0 z-40 border-b-2 border-azul-dark">
    <div class="flex items-center gap-2.5 text-[1.15rem] font-bold tracking-wide">
        <span class="text-amarelo text-[1.1rem]"><i class="fa-solid fa-lightbulb"></i></span>
        <span>Poste Queimado</span>
    </div>
    <div class="flex items-center gap-2">
        @php $navLink = 'text-white/85 text-sm px-2.5 py-1.5 rounded-md hover:bg-white/15 hover:text-white transition hidden sm:inline-block'; @endphp
        @php $navActive = 'bg-white/20 text-white font-semibold'; @endphp
        @if(auth()->user()->tipo === 'ADMIN')
            <a href="{{ route('admin.dashboard') }}" class="{{ $navLink }} {{ request()->routeIs('admin.dashboard') ? $navActive : '' }}">Dashboard</a>
            <a href="{{ route('admin.lista') }}"      class="{{ $navLink }} {{ request()->routeIs('admin.lista')      ? $navActive : '' }}">Lista</a>
            <a href="{{ route('admin.mapa') }}"       class="{{ $navLink }} {{ request()->routeIs('admin.mapa')       ? $navActive : '' }}">Mapa</a>
        @else
            <a href="{{ route('home') }}"              class="{{ $navLink }} {{ request()->routeIs('home')              ? $navActive : '' }}">Início</a>
            <a href="{{ route('registros.index') }}"   class="{{ $navLink }} {{ request()->routeIs('registros.index')   ? $navActive : '' }}">Meus Registros</a>
            <a href="{{ route('registros.create') }}"  class="{{ $navLink }} {{ request()->routeIs('registros.create')  ? $navActive : '' }}">+ Registrar</a>
        @endif
        <form action="{{ route('logout') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-transparent border border-white/45 text-white/90 px-3 py-1 rounded-md cursor-pointer text-[.83rem] hover:bg-white/15 hover:text-white transition">
                <i class="fa-solid fa-arrow-right-from-bracket sm:hidden"></i>
                <span class="hidden sm:inline">Sair ({{ auth()->user()->nome }})</span>
            </button>
        </form>
    </div>
</nav>
@endauth

<main class="p-5 max-w-3xl mx-auto">
    @if(session('sucesso'))
        <div class="px-4 py-3 rounded-lg mb-4 text-sm bg-green-50 text-green-900 border-l-4 border-verde">{{ session('sucesso') }}</div>
    @endif
    @if($errors->any())
        <div class="px-4 py-3 rounded-lg mb-4 text-sm bg-red-50 text-red-900 border-l-4 border-red-600">
            <ul class="pl-4 list-disc">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
    @endif

    @yield('content')
</main>

@auth
@if(auth()->user()->tipo === 'COMUM')
@php
    $bnLink   = 'relative flex flex-col items-center text-[.7rem] text-gray-500 gap-0.5 px-3 py-1.5 rounded-lg hover:text-azul hover:bg-azul/5 transition';
    $bnActive = 'text-azul before:content-[""] before:absolute before:-top-px before:left-1/4 before:right-1/4 before:h-[3px] before:bg-azul before:rounded-b';
@endphp
<nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-borda shadow-btm-nav z-40 flex items-center justify-center">
    <div class="flex items-center justify-around w-full max-w-3xl h-[60px] px-2 md:px-10">
        <a href="{{ route('home') }}"             class="{{ $bnLink }} {{ request()->routeIs('home')             ? $bnActive : '' }}"><i class="fa-solid fa-house"></i><span class="text-[.65rem]">Início</span></a>
        <a href="{{ route('registros.index') }}"  class="{{ $bnLink }} {{ request()->routeIs('registros.index')  ? $bnActive : '' }}"><i class="fa-solid fa-list"></i><span class="text-[.65rem]">Registros</span></a>
        <a href="{{ route('registros.create') }}" class="bg-azul text-white w-12 h-12 rounded-full flex items-center justify-center text-xl shadow-card -mt-5 shrink-0 hover:bg-azul-dark hover:scale-110 transition"><i class="fa-solid fa-plus"></i></a>
        <a href="#"                               class="{{ $bnLink }} {{ request()->routeIs('perfil')           ? $bnActive : '' }}"><i class="fa-solid fa-user"></i><span class="text-[.65rem]">Perfil</span></a>
    </div>
</nav>
@endif
@endauth

@yield('scripts')
</body>
</html>
