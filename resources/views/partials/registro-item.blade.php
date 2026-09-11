{{--
    Item de lista de registro (usado no Início e em Meus Registros).
      $r     : modelo Registro (com fotos carregadas)
      $meta  : 'data' (padrão) mostra só a data · 'completo' inclui descrição
--}}
@php $meta = $meta ?? 'data'; @endphp
<a href="{{ route('registros.show', $r->id) }}"
   class="group flex items-start gap-3.5 px-4 sm:px-5 py-4 border-b border-line-soft last:border-0 hover:bg-brand-50/40 transition">

    @if($r->fotos->count())
        <img src="{{ asset('storage/'.$r->fotos->first()->caminho_arquivo) }}" alt=""
             loading="lazy"
             class="w-14 h-14 object-cover rounded-xl shrink-0 border border-line">
    @else
        <div class="w-14 h-14 shrink-0 rounded-xl bg-pend-bg text-pend-dot grid place-items-center text-xl border border-pend-dot/20">
            <i class="fa-solid fa-lightbulb"></i>
        </div>
    @endif

    <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between gap-2">
            <h3 class="text-[.95rem] font-semibold text-ink truncate group-hover:text-brand-700 transition">
                {{ $r->titulo }}
            </h3>
            @include('partials.status-badge', ['status' => $r->status, 'extra' => 'shrink-0'])
        </div>

        <p class="text-[.8rem] text-ink-soft mt-1 flex items-center gap-1.5 min-w-0">
            <i class="fa-solid fa-location-dot text-ink-muted text-[.7rem] shrink-0"></i>
            <span class="truncate">{{ $r->endereco_texto }}</span>
        </p>

        @if($meta === 'completo' && $r->descricao)
            <p class="text-[.8rem] text-ink-muted mt-1 line-clamp-1">{{ Str::limit($r->descricao, 70) }}</p>
        @endif

        <p class="text-[.72rem] text-ink-muted mt-1.5">
            <i class="fa-regular fa-clock"></i>
            {{ $r->created_at->translatedFormat('d \d\e M, Y') }}
            <span class="text-line mx-1">·</span>
            #{{ $r->id }}
        </p>
    </div>

    <i class="fa-solid fa-chevron-right text-line text-xs mt-5 shrink-0 group-hover:text-brand-500 group-hover:translate-x-0.5 transition"></i>
</a>
