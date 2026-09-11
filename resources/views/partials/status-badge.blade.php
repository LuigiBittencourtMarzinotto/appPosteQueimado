{{--
    Selo de status.
      $status : PENDENTE | EM_ANDAMENTO | RESOLVIDO
      $size   : 'sm' (padrão) | 'md'
      $extra  : classes Tailwind adicionais
--}}
@php
    $size  = $size  ?? 'sm';
    $extra = $extra ?? '';

    [$tone, $dot, $label, $icon] = match($status) {
        'PENDENTE'     => ['bg-pend-bg text-pend-fg ring-pend-dot/25', 'bg-pend-dot', 'Pendente',     'fa-clock'],
        'EM_ANDAMENTO' => ['bg-and-bg  text-and-fg  ring-and-dot/25',  'bg-and-dot',  'Em andamento', 'fa-screwdriver-wrench'],
        'RESOLVIDO'    => ['bg-res-bg  text-res-fg  ring-res-dot/25',  'bg-res-dot',  'Resolvido',    'fa-circle-check'],
        default        => ['bg-slate-100 text-ink-soft ring-slate-300/40', 'bg-slate-400', $status ?: '—', 'fa-circle-question'],
    };

    $sizing = $size === 'md' ? 'text-[.8rem] px-3 py-1' : 'text-[.7rem] px-2.5 py-[3px]';
@endphp
<span class="inline-flex items-center gap-1.5 rounded-full font-semibold whitespace-nowrap ring-1 {{ $tone }} {{ $sizing }} {{ $extra }}"
      title="Status: {{ $label }}">
    <span class="w-1.5 h-1.5 rounded-full {{ $dot }}" aria-hidden="true"></span>{{ $label }}
</span>
