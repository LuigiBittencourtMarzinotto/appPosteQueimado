{{--
    Cabeçalho de página com voltar + título + ação opcional.
      $titulo   : texto do h1
      $back     : URL do botão voltar (opcional)
      $subtitulo: linha de apoio (opcional)
      $acao     : HTML já renderizado à direita (opcional)
--}}
@php
    $back      = $back      ?? null;
    $subtitulo = $subtitulo ?? null;
    $acao      = $acao      ?? null;
@endphp
<div class="flex items-start justify-between gap-3 mb-5">
    <div class="flex items-start gap-3 min-w-0">
        @if($back)
            <a href="{{ $back }}" aria-label="Voltar"
               class="shrink-0 mt-0.5 w-9 h-9 grid place-items-center rounded-xl bg-white text-brand-600 border border-line shadow-xs hover:bg-brand-50 hover:border-brand-200 active:scale-95 transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
        @endif
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-ink truncate">{{ $titulo }}</h1>
            @if($subtitulo)
                <p class="text-sm text-ink-muted mt-0.5">{{ $subtitulo }}</p>
            @endif
        </div>
    </div>
    @if($acao)
        <div class="shrink-0">{!! $acao !!}</div>
    @endif
</div>
