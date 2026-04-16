{{-- Status badge. Pass `$status` (PENDENTE|EM_ANDAMENTO|RESOLVIDO). Optional `$extra` for extra Tailwind classes. --}}
@php
    $extra = $extra ?? '';
    $cfg = match($status) {
        'PENDENTE'     => ['bg-pend-bg text-pend-fg', 'Pendente'],
        'EM_ANDAMENTO' => ['bg-and-bg text-and-fg',   'Em andamento'],
        'RESOLVIDO'    => ['bg-res-bg text-res-fg',   'Resolvido'],
        default        => ['bg-gray-100 text-gray-600', $status],
    };
@endphp
<span class="inline-block px-2.5 py-0.5 rounded-xl text-xs font-semibold {{ $cfg[0] }} {{ $extra }}">{{ $cfg[1] }}</span>
