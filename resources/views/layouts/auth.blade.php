<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="theme-color" content="#0D47A1">
    <title>@yield('title', 'Acesso') — Poste Queimado</title>
    @include('partials.head-assets')
</head>
<body class="font-sans text-ink bg-canvas antialiased">

<div class="min-h-screen lg:grid lg:grid-cols-[1.1fr_1fr]">

    {{-- ═══════════ Painel da marca (só em telas grandes) ═══════════ --}}
    <aside class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-brand-700 via-brand-600 to-brand-800 text-white p-12 relative overflow-hidden">
        {{-- Halo decorativo --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full bg-amarelo/10 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-32 -left-20 w-80 h-80 rounded-full bg-white/5 blur-3xl" aria-hidden="true"></div>

        <div class="relative flex items-center gap-3">
            <span class="w-11 h-11 rounded-2xl bg-white/15 grid place-items-center text-amarelo text-xl">
                <i class="fa-solid fa-lightbulb"></i>
            </span>
            <span class="font-bold text-lg tracking-tight">Poste Queimado</span>
        </div>

        <div class="relative max-w-md">
            <h2 class="text-4xl font-extrabold leading-tight tracking-tight">
                Sua rua merece<br>estar bem iluminada.
            </h2>
            <p class="mt-4 text-white/75 leading-relaxed">
                Registre um poste apagado em menos de um minuto e acompanhe o reparo até a conclusão.
            </p>

            <ul class="mt-9 space-y-4">
                @foreach([
                    ['fa-location-crosshairs', 'Localize em segundos',   'Use o GPS ou digite o endereço — o mapa acha o ponto.'],
                    ['fa-camera',              'Anexe uma foto',          'Uma imagem ajuda a equipe a resolver mais rápido.'],
                    ['fa-bell',                'Acompanhe o andamento',   'Veja o status mudar de pendente até resolvido.'],
                ] as [$icone, $titulo, $texto])
                    <li class="flex items-start gap-3.5">
                        <span class="mt-0.5 w-9 h-9 shrink-0 rounded-xl bg-white/15 grid place-items-center text-amarelo">
                            <i class="fa-solid {{ $icone }} text-sm"></i>
                        </span>
                        <span>
                            <span class="block font-semibold text-[.95rem]">{{ $titulo }}</span>
                            <span class="block text-sm text-white/65 leading-snug">{{ $texto }}</span>
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <p class="relative text-xs text-white/45">Iluminação pública · Prefeitura Municipal</p>
    </aside>

    {{-- ═══════════ Formulário ═══════════ --}}
    <main class="flex items-center justify-center px-5 py-10 sm:px-8 min-h-screen lg:min-h-0">
        <div class="w-full max-w-[26rem] animate-fade-up">

            {{-- Marca no mobile --}}
            <div class="lg:hidden text-center mb-8">
                <div class="mx-auto w-16 h-16 rounded-2xl bg-brand-600 text-amarelo grid place-items-center text-3xl shadow-hero mb-3">
                    <i class="fa-solid fa-lightbulb"></i>
                </div>
                <h1 class="text-xl font-bold tracking-tight text-brand-700">Poste Queimado</h1>
                <p class="text-sm text-ink-muted mt-0.5">Iluminação pública da sua cidade</p>
            </div>

            @yield('form')
        </div>
    </main>
</div>

<script>
(() => {
    /* Mostrar / esconder senha */
    document.querySelectorAll('[data-toggle-senha]').forEach(btn => {
        btn.addEventListener('click', () => {
            const campo = document.getElementById(btn.dataset.toggleSenha);
            if (!campo) return;
            const revelado = campo.type === 'text';
            campo.type = revelado ? 'password' : 'text';
            btn.querySelector('i').className = revelado ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash';
            btn.setAttribute('aria-label', revelado ? 'Mostrar senha' : 'Esconder senha');
        });
    });

    /* Contas de teste: um clique preenche o formulário */
    document.querySelectorAll('[data-conta]').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('email').value = btn.dataset.email;
            document.getElementById('senha').value = btn.dataset.senha;
            document.getElementById('senha').focus();
        });
    });

    /* Evita duplo envio */
    document.querySelectorAll('form[data-loading]').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('[type=submit]');
            if (!btn || btn.dataset.busy) return;
            btn.dataset.busy = '1';
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> ' + (btn.dataset.loadingText || 'Aguarde...');
            setTimeout(() => { btn.disabled = true; }, 0);
        });
    });
})();
</script>

@yield('scripts')
</body>
</html>
