{{--
    Estado vazio.
      $icone  : classe FontAwesome (padrão: lâmpada)
      $titulo : frase principal
      $texto  : apoio (opcional)
      $ctaUrl / $ctaLabel / $ctaIcone : botão principal (opcional)
--}}
@php
    $icone    = $icone    ?? 'fa-lightbulb';
    $texto    = $texto    ?? null;
    $ctaUrl   = $ctaUrl   ?? null;
    $ctaLabel = $ctaLabel ?? null;
    $ctaIcone = $ctaIcone ?? 'fa-plus';
@endphp
<div class="text-center px-6 py-12 animate-fade-in">
    <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-50 text-brand-500 grid place-items-center text-2xl mb-4">
        <i class="fa-solid {{ $icone }}"></i>
    </div>
    <p class="font-semibold text-ink">{{ $titulo }}</p>
    @if($texto)
        <p class="text-sm text-ink-muted mt-1 max-w-xs mx-auto leading-relaxed">{{ $texto }}</p>
    @endif
    @if($ctaUrl)
        <a href="{{ $ctaUrl }}"
           class="inline-flex items-center gap-2 mt-5 bg-brand-600 text-white px-5 py-2.5 rounded-xl font-semibold text-sm shadow-xs hover:bg-brand-700 active:scale-[.98] transition">
            <i class="fa-solid {{ $ctaIcone }}"></i> {{ $ctaLabel ?? 'Criar' }}
        </a>
    @endif
</div>
