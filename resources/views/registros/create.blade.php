@extends('layouts.app')
@section('title', '— Registrar Problema')

@section('content')
@php
    $input = 'w-full px-4 py-3 border border-line rounded-xl text-[.95rem] bg-white placeholder:text-ink-muted/70 focus:outline-none focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition';
    $label = 'block text-[.85rem] font-semibold text-ink mb-1.5';
    $hint  = 'text-[.78rem] text-ink-muted leading-snug';
@endphp

@include('partials.page-header', [
    'titulo'    => 'Registrar poste queimado',
    'subtitulo' => 'Três passos rápidos. Só o essencial é obrigatório.',
    'back'      => route('home'),
])

<form method="POST" action="{{ route('registros.store') }}" enctype="multipart/form-data" id="form-registro" novalidate>
    @csrf

    {{-- ═══════════ 1. O problema ═══════════ --}}
    <section class="bg-surface rounded-3xl border border-line shadow-card p-5 sm:p-6 mb-4">
        <header class="flex items-center gap-3 mb-5">
            <span class="w-8 h-8 shrink-0 rounded-full bg-brand-600 text-white grid place-items-center text-[.78rem] font-bold">1</span>
            <div>
                <h2 class="font-semibold text-ink leading-tight">O problema</h2>
                <p class="{{ $hint }}">Um título curto e uma descrição do que está acontecendo.</p>
            </div>
        </header>

        <div class="mb-5">
            <label for="titulo" class="{{ $label }}">
                Título <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <input type="text" id="titulo" name="titulo" class="{{ $input }}" maxlength="200"
                   value="{{ old('titulo') }}" placeholder="Ex: Poste apagado na esquina da praça" required
                   aria-describedby="titulo-ajuda">
            <div class="flex items-start justify-between gap-3 mt-1.5">
                <p id="titulo-ajuda" class="{{ $hint }}">Ou escolha uma sugestão:</p>
                <span data-contador="titulo" class="text-[.72rem] text-ink-muted tabular-nums shrink-0">0/200</span>
            </div>
            <div class="flex gap-2 flex-wrap mt-2">
                @foreach([
                    'Poste apagado',
                    'Lâmpada piscando',
                    'Poste aceso de dia',
                    'Vários postes apagados na rua',
                ] as $sugestao)
                    <button type="button" data-sugestao-titulo="{{ $sugestao }}"
                            class="px-3 py-1.5 rounded-full bg-slate-100 text-ink-soft text-[.75rem] font-medium hover:bg-brand-50 hover:text-brand-700 transition">
                        {{ $sugestao }}
                    </button>
                @endforeach
            </div>
        </div>

        <div>
            <label for="descricao" class="{{ $label }}">
                Descrição <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <textarea id="descricao" name="descricao" class="{{ $input }} resize-y" rows="4" maxlength="1000"
                      placeholder="Ex: O poste está apagado há cerca de uma semana. A rua fica muito escura à noite e há um ponto de ônibus ao lado."
                      required aria-describedby="descricao-ajuda">{{ old('descricao') }}</textarea>
            <div class="flex items-start justify-between gap-3 mt-1.5">
                <p id="descricao-ajuda" class="{{ $hint }}">Quanto mais detalhes, mais rápido a equipe resolve.</p>
                <span data-contador="descricao" class="text-[.72rem] text-ink-muted tabular-nums shrink-0">0/1000</span>
            </div>
        </div>
    </section>

    {{-- ═══════════ 2. Onde fica ═══════════ --}}
    <section class="bg-surface rounded-3xl border border-line shadow-card p-5 sm:p-6 mb-4">
        <header class="flex items-center gap-3 mb-5">
            <span class="w-8 h-8 shrink-0 rounded-full bg-brand-600 text-white grid place-items-center text-[.78rem] font-bold">2</span>
            <div>
                <h2 class="font-semibold text-ink leading-tight">Onde fica</h2>
                <p class="{{ $hint }}">Use sua localização ou digite o endereço — depois ajuste o ponto no mapa.</p>
            </div>
        </header>

        {{-- Botão de GPS --}}
        <button type="button" id="btn-gps"
                class="w-full flex items-center justify-center gap-2.5 mb-4 px-4 py-3 rounded-xl border-2 border-brand-200 bg-brand-50 text-brand-700
                       font-semibold text-[.9rem] hover:bg-brand-100 hover:border-brand-300 active:scale-[.99] transition">
            <i class="fa-solid fa-location-crosshairs"></i> Usar minha localização atual
        </button>

        <div class="relative mb-4">
            <label for="endereco_texto" class="{{ $label }}">
                Endereço <span class="text-red-500" aria-hidden="true">*</span>
            </label>
            <div class="relative">
                <i class="fa-solid fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-ink-muted text-sm pointer-events-none"></i>
                <input type="text" id="endereco_texto" name="endereco_texto" class="{{ $input }} pl-11 pr-10" maxlength="255"
                       value="{{ old('endereco_texto') }}" placeholder="Ex: Rua das Flores, 123, Curitiba" required
                       autocomplete="off" role="combobox" aria-expanded="false" aria-controls="sugestoes"
                       aria-describedby="geo-status">
                <span id="geo-icone" class="absolute right-4 top-1/2 -translate-y-1/2 text-sm hidden" aria-hidden="true"></span>
            </div>

            {{-- Sugestões de endereço --}}
            <ul id="sugestoes" role="listbox" aria-label="Endereços encontrados" hidden
                class="absolute z-30 left-0 right-0 mt-1.5 bg-white border border-line rounded-xl shadow-pop overflow-hidden max-h-64 overflow-y-auto animate-pop-in"></ul>

            <p id="geo-status" class="{{ $hint }} mt-1.5" aria-live="polite">
                Digite ao menos 4 caracteres para buscar no mapa.
            </p>
        </div>

        {{-- Mapa --}}
        <div>
            <div class="flex items-center justify-between gap-3 mb-2">
                <span class="{{ $label }} mb-0">
                    Ponto exato <span class="text-red-500" aria-hidden="true">*</span>
                </span>
                <button type="button" id="btn-recentrar"
                        class="text-[.76rem] font-semibold text-brand-600 hover:underline hidden">
                    <i class="fa-solid fa-crosshairs"></i> Centralizar no ponto
                </button>
            </div>

            <div class="relative rounded-2xl overflow-hidden border border-line">
                <div id="mapa"></div>
                <div class="absolute top-2.5 left-2.5 right-2.5 pointer-events-none">
                    <p class="inline-flex items-center gap-2 bg-white/95 backdrop-blur text-ink-soft text-[.74rem] font-medium px-3 py-1.5 rounded-lg shadow-xs">
                        <i class="fa-solid fa-hand-pointer text-brand-600"></i>
                        Toque no mapa ou arraste o marcador para ajustar
                    </p>
                </div>
            </div>

            <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
            <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}">

            {{-- Estado do ponto --}}
            <div id="coord-info" class="mt-2 flex items-start gap-2 px-3.5 py-2.5 rounded-xl text-[.8rem] font-medium
                        {{ old('lat') ? 'bg-res-bg text-res-fg' : 'bg-pend-bg text-pend-fg' }}">
                <i class="fa-solid {{ old('lat') ? 'fa-circle-check' : 'fa-circle-exclamation' }} mt-0.5" data-coord-icone></i>
                <span data-coord-texto>
                    @if(old('lat'))
                        Ponto marcado em {{ number_format((float) old('lat'), 5, '.', '') }}, {{ number_format((float) old('lng'), 5, '.', '') }}
                    @else
                        Nenhum ponto marcado ainda
                    @endif
                </span>
            </div>

            {{-- Endereço detectado ao mover o marcador --}}
            <div id="reverso" hidden
                 class="mt-2 flex items-start gap-2.5 px-3.5 py-3 rounded-xl bg-brand-50 border border-brand-200 text-[.82rem]">
                <i class="fa-solid fa-wand-magic-sparkles text-brand-600 mt-0.5"></i>
                <div class="flex-1 min-w-0">
                    <p class="text-ink-soft">Endereço deste ponto:</p>
                    <p data-reverso-texto class="font-semibold text-ink mt-0.5 break-words"></p>
                    <button type="button" data-reverso-usar
                            class="mt-2 inline-flex items-center gap-1.5 text-[.78rem] font-bold text-brand-700 hover:underline">
                        <i class="fa-solid fa-arrow-up"></i> Usar como endereço
                    </button>
                </div>
                <button type="button" data-reverso-fechar aria-label="Descartar sugestão"
                        class="text-ink-muted hover:text-ink p-1 leading-none">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </section>

    {{-- ═══════════ 3. Foto ═══════════ --}}
    <section class="bg-surface rounded-3xl border border-line shadow-card p-5 sm:p-6 mb-4">
        <header class="flex items-center gap-3 mb-5">
            <span class="w-8 h-8 shrink-0 rounded-full bg-slate-200 text-ink-soft grid place-items-center text-[.78rem] font-bold">3</span>
            <div>
                <h2 class="font-semibold text-ink leading-tight">
                    Foto
                    <span class="ml-1 text-[.7rem] font-semibold uppercase tracking-wide text-ink-muted bg-slate-100 px-1.5 py-0.5 rounded">opcional</span>
                </h2>
                <p class="{{ $hint }}">Ajuda a equipe a identificar o poste certo.</p>
            </div>
        </header>

        <div id="upload-area" tabindex="0" role="button"
             aria-label="Adicionar foto do problema"
             class="border-2 border-dashed border-line rounded-2xl px-6 py-9 text-center cursor-pointer
                    hover:border-brand-400 hover:bg-brand-50/40 transition">
            <div class="w-12 h-12 mx-auto rounded-xl bg-brand-50 text-brand-500 grid place-items-center text-xl mb-3">
                <i class="fa-solid fa-camera"></i>
            </div>
            <p class="text-[.9rem] font-semibold text-ink">Toque para escolher ou tirar uma foto</p>
            <p class="{{ $hint }} mt-1">Você também pode arrastar a imagem até aqui · JPG ou PNG · máx. 5 MB</p>
        </div>

        <input type="file" id="foto" name="foto" accept="image/*" class="hidden">

        {{-- Pré-visualização --}}
        <figure id="foto-box" hidden class="relative mt-1">
            <img id="foto-preview" alt="Pré-visualização da foto escolhida"
                 class="w-full max-h-64 object-cover rounded-2xl border border-line">
            <button type="button" id="foto-remover"
                    class="absolute top-2.5 right-2.5 inline-flex items-center gap-1.5 bg-white/95 backdrop-blur text-red-600 text-[.78rem] font-semibold
                           px-3 py-1.5 rounded-lg shadow-xs hover:bg-white transition">
                <i class="fa-solid fa-trash-can"></i> Remover
            </button>
            <figcaption id="foto-nome" class="{{ $hint }} mt-2 truncate"></figcaption>
        </figure>

        <p id="foto-erro" hidden class="mt-2 text-[.8rem] font-medium text-red-600"></p>
    </section>

    {{-- ═══════════ Envio ═══════════ --}}
    <div class="sticky bottom-[74px] md:bottom-4 z-30">
        <div class="bg-white/95 backdrop-blur border border-line rounded-2xl shadow-pop p-3.5">
            <ul id="checklist" class="flex flex-wrap gap-x-4 gap-y-1.5 mb-3 text-[.76rem]">
                @foreach([
                    ['titulo',    'Título'],
                    ['descricao', 'Descrição'],
                    ['endereco',  'Endereço'],
                    ['ponto',     'Ponto no mapa'],
                ] as [$chave, $rotulo])
                    <li data-check="{{ $chave }}" class="flex items-center gap-1.5 text-ink-muted font-medium">
                        <i class="fa-regular fa-circle text-[.7rem]"></i>{{ $rotulo }}
                    </li>
                @endforeach
            </ul>
            <button type="submit" id="btn-enviar"
                    class="w-full inline-flex items-center justify-center gap-2.5 bg-brand-600 text-white py-3.5 rounded-xl font-bold
                           shadow-xs hover:bg-brand-700 active:scale-[.99] disabled:opacity-70 disabled:cursor-wait transition">
                <i class="fa-solid fa-paper-plane"></i> Enviar registro
            </button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
(() => {
    /* ═════════ Mapa ═════════ */
    const CURITIBA = [-25.4290, -49.2671];
    const latInput = document.getElementById('lat');
    const lngInput = document.getElementById('lng');
    const temPontoSalvo = latInput.value !== '' && lngInput.value !== '';
    const inicio = temPontoSalvo ? [+latInput.value, +lngInput.value] : CURITIBA;

    const map = L.map('mapa', { zoomControl: true }).setView(inicio, temPontoSalvo ? 17 : 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap', maxZoom: 19,
    }).addTo(map);
    setTimeout(() => map.invalidateSize(), 120);

    const iconePino = L.divIcon({ html: '<div class="pin-pulse"></div>', className: '', iconSize: [18, 18], iconAnchor: [9, 9] });

    let marcador = null;
    const coordBox    = document.getElementById('coord-info');
    const coordIcone  = coordBox.querySelector('[data-coord-icone]');
    const coordTexto  = coordBox.querySelector('[data-coord-texto]');
    const btnRecentrar= document.getElementById('btn-recentrar');

    function marcarPonto(lat, lng, zoom, buscarEnderecoDoPonto = true) {
        latInput.value = (+lat).toFixed(7);
        lngInput.value = (+lng).toFixed(7);

        if (marcador) {
            marcador.setLatLng([lat, lng]);
        } else {
            marcador = L.marker([lat, lng], { icon: iconePino, draggable: true }).addTo(map);
            marcador.on('dragend', e => {
                const p = e.target.getLatLng();
                marcarPonto(p.lat, p.lng, null);
            });
        }
        if (zoom !== null) map.setView([lat, lng], zoom ?? map.getZoom());

        coordBox.className = 'mt-2 flex items-start gap-2 px-3.5 py-2.5 rounded-xl text-[.8rem] font-medium bg-res-bg text-res-fg';
        coordIcone.className = 'fa-solid fa-circle-check mt-0.5';
        coordTexto.textContent = `Ponto marcado em ${(+lat).toFixed(5)}, ${(+lng).toFixed(5)}`;
        btnRecentrar.classList.remove('hidden');

        atualizarChecklist();
        if (buscarEnderecoDoPonto) buscarEnderecoReverso(lat, lng);
    }

    map.on('click', e => marcarPonto(e.latlng.lat, e.latlng.lng, null));
    btnRecentrar.addEventListener('click', () => {
        if (marcador) map.setView(marcador.getLatLng(), 17);
    });

    /* ═════════ GPS do aparelho ═════════ */
    const btnGps = document.getElementById('btn-gps');
    btnGps.addEventListener('click', () => {
        if (!navigator.geolocation) {
            mostrarStatus('Seu navegador não permite localização automática.', 'erro');
            return;
        }
        const htmlOriginal = btnGps.innerHTML;
        btnGps.disabled = true;
        btnGps.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Localizando você...';

        navigator.geolocation.getCurrentPosition(
            pos => {
                btnGps.disabled = false;
                btnGps.innerHTML = htmlOriginal;
                marcarPonto(pos.coords.latitude, pos.coords.longitude, 18);
                mostrarStatus('Localização encontrada. Confira o endereço abaixo do mapa.', 'ok');
            },
            () => {
                btnGps.disabled = false;
                btnGps.innerHTML = htmlOriginal;
                mostrarStatus('Não foi possível obter sua localização. Digite o endereço ou toque no mapa.', 'erro');
            },
            { enableHighAccuracy: true, timeout: 10000 }
        );
    });

    /* ═════════ Busca de endereço (Nominatim) ═════════ */
    const enderecoEl  = document.getElementById('endereco_texto');
    const statusEl    = document.getElementById('geo-status');
    const iconeEl     = document.getElementById('geo-icone');
    const sugestoesEl = document.getElementById('sugestoes');
    let temporizador = null, requisicao = null;

    function mostrarStatus(msg, tipo) {
        const cores = { ok: 'text-res-fg', erro: 'text-red-600', aviso: 'text-pend-fg', neutro: 'text-ink-muted' };
        statusEl.className = `text-[.78rem] leading-snug mt-1.5 font-medium ${cores[tipo] || cores.neutro}`;
        statusEl.textContent = msg;
    }

    function mostrarIcone(classe) {
        iconeEl.className = classe ? `absolute right-4 top-1/2 -translate-y-1/2 text-sm ${classe}` : 'hidden';
        iconeEl.hidden = !classe;
    }

    function fecharSugestoes() {
        sugestoesEl.hidden = true;
        sugestoesEl.innerHTML = '';
        enderecoEl.setAttribute('aria-expanded', 'false');
    }

    enderecoEl.addEventListener('input', () => {
        clearTimeout(temporizador);
        requisicao?.abort();
        atualizarChecklist();
        fecharSugestoes();

        const termo = enderecoEl.value.trim();
        if (termo.length < 4) {
            mostrarIcone(null);
            mostrarStatus('Digite ao menos 4 caracteres para buscar no mapa.', 'neutro');
            return;
        }
        mostrarIcone('fa-solid fa-spinner fa-spin text-ink-muted');
        mostrarStatus('Buscando endereços...', 'neutro');
        temporizador = setTimeout(() => buscarEndereco(termo), 550);
    });

    async function buscarEndereco(termo) {
        requisicao = new AbortController();
        try {
            const url = 'https://nominatim.openstreetmap.org/search'
                      + '?format=json&limit=5&addressdetails=1&countrycodes=br&accept-language=pt-BR'
                      + '&q=' + encodeURIComponent(termo);
            const resposta = await fetch(url, { signal: requisicao.signal, headers: { Accept: 'application/json' } });
            const dados = await resposta.json();

            if (!dados.length) {
                mostrarIcone('fa-solid fa-circle-exclamation text-pend-fg');
                mostrarStatus('Endereço não encontrado. Toque no mapa para marcar o local.', 'aviso');
                return;
            }

            // Um único resultado: marca direto. Vários: mostra a lista para o usuário escolher.
            if (dados.length === 1) {
                mostrarIcone('fa-solid fa-circle-check text-res-fg');
                mostrarStatus('Endereço localizado no mapa.', 'ok');
                marcarPonto(dados[0].lat, dados[0].lon, 17, false);
                return;
            }

            mostrarIcone('fa-solid fa-list text-brand-600');
            mostrarStatus(`${dados.length} endereços encontrados — escolha o correto.`, 'neutro');
            renderizarSugestoes(dados);
        } catch (erro) {
            if (erro.name === 'AbortError') return;
            mostrarIcone('fa-solid fa-triangle-exclamation text-red-600');
            mostrarStatus('Falha ao buscar o endereço. Toque no mapa para marcar o local.', 'erro');
        }
    }

    function renderizarSugestoes(dados) {
        sugestoesEl.innerHTML = dados.map((item, i) => `
            <li role="option" tabindex="0" data-lat="${item.lat}" data-lon="${item.lon}"
                data-nome="${item.display_name.replace(/"/g, '&quot;')}"
                class="flex items-start gap-3 px-4 py-3 cursor-pointer border-b border-line-soft last:border-0 hover:bg-brand-50 transition">
                <i class="fa-solid fa-location-dot text-brand-500 mt-1 text-[.8rem]"></i>
                <span class="text-[.83rem] text-ink leading-snug">${item.display_name}</span>
            </li>`).join('');
        sugestoesEl.hidden = false;
        enderecoEl.setAttribute('aria-expanded', 'true');

        sugestoesEl.querySelectorAll('[role=option]').forEach(opcao => {
            const escolher = () => {
                enderecoEl.value = opcao.dataset.nome;
                marcarPonto(opcao.dataset.lat, opcao.dataset.lon, 17, false);
                fecharSugestoes();
                mostrarIcone('fa-solid fa-circle-check text-res-fg');
                mostrarStatus('Endereço localizado no mapa.', 'ok');
                atualizarChecklist();
            };
            opcao.addEventListener('click', escolher);
            opcao.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); escolher(); } });
        });
    }

    document.addEventListener('click', e => {
        if (!sugestoesEl.contains(e.target) && e.target !== enderecoEl) fecharSugestoes();
    });
    enderecoEl.addEventListener('keydown', e => {
        if (e.key === 'Escape') fecharSugestoes();
        if (e.key === 'ArrowDown') sugestoesEl.querySelector('[role=option]')?.focus();
    });

    /* ═════════ Endereço a partir do ponto (geocodificação reversa) ═════════ */
    const reversoBox   = document.getElementById('reverso');
    const reversoTexto = reversoBox.querySelector('[data-reverso-texto]');
    let reversoCtrl = null;

    async function buscarEnderecoReverso(lat, lng) {
        reversoCtrl?.abort();
        reversoCtrl = new AbortController();
        try {
            const url = 'https://nominatim.openstreetmap.org/reverse'
                      + `?format=json&accept-language=pt-BR&lat=${lat}&lon=${lng}`;
            const resposta = await fetch(url, { signal: reversoCtrl.signal, headers: { Accept: 'application/json' } });
            const dados = await resposta.json();
            if (!dados?.display_name) return;

            // Se o campo está vazio, preenche direto; se já tem texto, oferece a troca.
            if (enderecoEl.value.trim() === '') {
                enderecoEl.value = dados.display_name;
                mostrarIcone('fa-solid fa-circle-check text-res-fg');
                mostrarStatus('Endereço preenchido a partir do ponto marcado.', 'ok');
                reversoBox.hidden = true;
                atualizarChecklist();
            } else if (dados.display_name !== enderecoEl.value.trim()) {
                reversoTexto.textContent = dados.display_name;
                reversoBox.hidden = false;
            }
        } catch (_) { /* silencioso: é só uma conveniência */ }
    }

    reversoBox.querySelector('[data-reverso-usar]').addEventListener('click', () => {
        enderecoEl.value = reversoTexto.textContent;
        reversoBox.hidden = true;
        mostrarIcone('fa-solid fa-circle-check text-res-fg');
        mostrarStatus('Endereço atualizado com o ponto do mapa.', 'ok');
        atualizarChecklist();
    });
    reversoBox.querySelector('[data-reverso-fechar]').addEventListener('click', () => { reversoBox.hidden = true; });

    /* ═════════ Contadores e sugestões de título ═════════ */
    document.querySelectorAll('[data-contador]').forEach(saida => {
        const campo = document.getElementById(saida.dataset.contador);
        const limite = campo.getAttribute('maxlength');
        const atualizar = () => {
            saida.textContent = `${campo.value.length}/${limite}`;
            saida.classList.toggle('text-pend-fg', campo.value.length > limite * 0.9);
        };
        campo.addEventListener('input', atualizar);
        atualizar();
    });

    document.querySelectorAll('[data-sugestao-titulo]').forEach(btn => {
        btn.addEventListener('click', () => {
            const campo = document.getElementById('titulo');
            campo.value = btn.dataset.sugestaoTitulo;
            campo.dispatchEvent(new Event('input'));
            campo.focus();
        });
    });

    /* ═════════ Foto ═════════ */
    const area     = document.getElementById('upload-area');
    const arquivo  = document.getElementById('foto');
    const caixa    = document.getElementById('foto-box');
    const preview  = document.getElementById('foto-preview');
    const nomeEl   = document.getElementById('foto-nome');
    const erroEl   = document.getElementById('foto-erro');
    const LIMITE   = 5 * 1024 * 1024;

    const abrirSeletor = () => arquivo.click();
    area.addEventListener('click', abrirSeletor);
    area.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); abrirSeletor(); } });

    ['dragenter', 'dragover'].forEach(evt => area.addEventListener(evt, e => {
        e.preventDefault();
        area.classList.add('border-brand-500', 'bg-brand-50');
    }));
    ['dragleave', 'drop'].forEach(evt => area.addEventListener(evt, e => {
        e.preventDefault();
        area.classList.remove('border-brand-500', 'bg-brand-50');
    }));
    area.addEventListener('drop', e => {
        const f = e.dataTransfer.files?.[0];
        if (!f) return;
        const dt = new DataTransfer();
        dt.items.add(f);
        arquivo.files = dt.files;
        arquivo.dispatchEvent(new Event('change'));
    });

    arquivo.addEventListener('change', () => {
        const f = arquivo.files?.[0];
        erroEl.hidden = true;
        if (!f) return;

        if (!f.type.startsWith('image/')) {
            erroEl.textContent = 'Escolha um arquivo de imagem (JPG ou PNG).';
            erroEl.hidden = false;
            arquivo.value = '';
            return;
        }
        if (f.size > LIMITE) {
            erroEl.textContent = `A imagem tem ${(f.size / 1048576).toFixed(1)} MB. O limite é 5 MB.`;
            erroEl.hidden = false;
            arquivo.value = '';
            return;
        }

        const leitor = new FileReader();
        leitor.onload = e => {
            preview.src = e.target.result;
            nomeEl.textContent = `${f.name} · ${(f.size / 1048576).toFixed(1)} MB`;
            caixa.hidden = false;
            area.hidden = true;
        };
        leitor.readAsDataURL(f);
    });

    document.getElementById('foto-remover').addEventListener('click', () => {
        arquivo.value = '';
        preview.src = '';
        caixa.hidden = true;
        area.hidden = false;
        erroEl.hidden = true;
    });

    /* ═════════ Checklist e envio ═════════ */
    const form    = document.getElementById('form-registro');
    const enviar  = document.getElementById('btn-enviar');
    const campos  = {
        titulo:    () => document.getElementById('titulo').value.trim().length > 0,
        descricao: () => document.getElementById('descricao').value.trim().length > 0,
        endereco:  () => enderecoEl.value.trim().length > 0,
        ponto:     () => latInput.value !== '' && lngInput.value !== '',
    };

    function atualizarChecklist() {
        Object.entries(campos).forEach(([chave, ok]) => {
            const item = document.querySelector(`[data-check="${chave}"]`);
            if (!item) return;
            const feito = ok();
            item.className = 'flex items-center gap-1.5 font-medium ' + (feito ? 'text-res-fg' : 'text-ink-muted');
            item.querySelector('i').className = feito
                ? 'fa-solid fa-circle-check text-[.7rem]'
                : 'fa-regular fa-circle text-[.7rem]';
        });
    }

    ['titulo', 'descricao', 'endereco_texto'].forEach(id =>
        document.getElementById(id).addEventListener('input', atualizarChecklist)
    );
    atualizarChecklist();

    // Restaura o pino quando o formulário volta com erros de validação do servidor.
    if (temPontoSalvo) marcarPonto(+latInput.value, +lngInput.value, 17, false);

    form.addEventListener('submit', e => {
        const faltando = Object.entries(campos).filter(([, ok]) => !ok()).map(([chave]) => chave);

        if (faltando.length) {
            e.preventDefault();
            const alvos = {
                titulo:    document.getElementById('titulo'),
                descricao: document.getElementById('descricao'),
                endereco:  enderecoEl,
                ponto:     document.getElementById('mapa'),
            };
            const primeiro = alvos[faltando[0]];
            primeiro.scrollIntoView({ behavior: 'smooth', block: 'center' });
            if (faltando[0] === 'ponto') {
                mostrarStatus('Falta marcar o ponto no mapa: toque no local exato do poste.', 'erro');
            } else {
                primeiro.focus({ preventScroll: true });
            }
            return;
        }

        enviar.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Enviando registro...';
        setTimeout(() => { enviar.disabled = true; }, 0);
    });
})();
</script>
@endsection
