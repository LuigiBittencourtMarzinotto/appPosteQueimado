{{--
    Trilha visual do andamento: Pendente → Em andamento → Resolvido.
      $status : status atual do registro
--}}
@php
    $etapas = [
        ['PENDENTE',     'Recebido',     'fa-inbox',              'A equipe já tem seu registro na fila.'],
        ['EM_ANDAMENTO', 'Em andamento', 'fa-screwdriver-wrench', 'O reparo foi iniciado.'],
        ['RESOLVIDO',    'Resolvido',    'fa-circle-check',       'O problema foi corrigido.'],
    ];
    $atual = array_search($status, array_column($etapas, 0), true);
    $atual = $atual === false ? 0 : $atual;
@endphp
<div class="flex items-start" role="list" aria-label="Andamento do registro">
    @foreach($etapas as $i => [$chave, $rotulo, $icone, $descricao])
        @php
            $concluida = $i < $atual;
            $ativa     = $i === $atual;
            $circulo   = $concluida ? 'bg-res-dot text-white'
                       : ($ativa    ? 'bg-brand-600 text-white ring-4 ring-brand-100'
                                    : 'bg-slate-100 text-ink-muted');
        @endphp
        <div role="listitem" class="flex-1 text-center relative" @if($ativa) aria-current="step" @endif>
            {{-- Linha de ligação --}}
            @if($i > 0)
                <span class="absolute top-[18px] right-1/2 w-full h-[3px] rounded-full {{ $i <= $atual ? 'bg-res-dot' : 'bg-line' }}"
                      aria-hidden="true"></span>
            @endif

            <span class="relative z-10 mx-auto w-10 h-10 rounded-full grid place-items-center text-sm {{ $circulo }}">
                <i class="fa-solid {{ $concluida ? 'fa-check' : $icone }}"></i>
            </span>
            <p class="text-[.78rem] font-semibold mt-2 {{ $ativa ? 'text-brand-700' : ($concluida ? 'text-res-fg' : 'text-ink-muted') }}">
                {{ $rotulo }}
            </p>
            @if($ativa)
                <p class="text-[.7rem] text-ink-muted mt-0.5 leading-snug px-1">{{ $descricao }}</p>
            @endif
        </div>
    @endforeach
</div>
