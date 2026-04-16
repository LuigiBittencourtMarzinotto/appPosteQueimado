@extends('layouts.app')
@section('title', '— Registrar Problema')

@section('content')
<div class="flex items-center gap-2.5 mb-5">
    <a href="{{ route('home') }}" class="text-azul text-2xl no-underline hover:opacity-80 transition">←</a>
    <h2 class="text-lg font-bold">Registrar Poste Queimado</h2>
</div>

<form method="POST" action="{{ route('registros.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="bg-white rounded-2xl shadow-card p-5 mb-4 hover:shadow-card-hover transition">
        @php $input = 'w-full px-3.5 py-2.5 border border-borda rounded-lg text-[.95rem] bg-white focus:outline-none focus:border-azul focus:ring-4 focus:ring-azul/10 transition'; @endphp
        @php $label = 'block text-sm font-medium mb-1 text-gray-600'; @endphp

        <div class="mb-4">
            <label for="titulo" class="{{ $label }}">Título <span class="text-red-600">*</span></label>
            <input type="text" id="titulo" name="titulo" class="{{ $input }}"
                   value="{{ old('titulo') }}" placeholder="Ex: Poste apagado na esquina" required>
        </div>

        <div class="mb-4">
            <label for="endereco_texto" class="{{ $label }}">
                Endereço <span class="text-red-600">*</span>
                <span id="geo-status" class="ml-2 text-xs font-normal text-gray-500"></span>
            </label>
            <input type="text" id="endereco_texto" name="endereco_texto" class="{{ $input }}"
                   value="{{ old('endereco_texto') }}" placeholder="Ex: Rua das Flores, 123, Curitiba" required>
            <p class="text-xs text-gray-500 mt-1">O mapa será atualizado automaticamente conforme você digita.</p>
        </div>

        <div class="mb-4">
            <label class="{{ $label }}">Localização no mapa <span class="text-red-600">*</span></label>
            <p class="text-xs text-gray-500 mb-1.5">Você pode clicar no mapa para ajustar o ponto exato.</p>
            <div id="mapa" class="rounded-lg border border-borda"></div>
            <input type="hidden" id="lat" name="lat" value="{{ old('lat') }}">
            <input type="hidden" id="lng" name="lng" value="{{ old('lng') }}">
            <p id="coord-info" class="text-xs text-gray-500 mt-1">
                @if(old('lat')) Selecionado: {{ old('lat') }}, {{ old('lng') }} @else Nenhum ponto selecionado @endif
            </p>
        </div>

        <div class="mb-4">
            <label for="descricao" class="{{ $label }}">Descrição do problema <span class="text-red-600">*</span></label>
            <textarea id="descricao" name="descricao" class="{{ $input }}"
                      rows="4" placeholder="Descreva o problema com detalhes..." required>{{ old('descricao') }}</textarea>
        </div>

        <div class="mb-2">
            <label class="{{ $label }}">Foto (opcional)</label>
            <div class="border-2 border-dashed border-borda rounded-lg p-6 text-center text-gray-500 cursor-pointer hover:border-azul hover:text-azul transition"
                 id="upload-area" onclick="document.getElementById('foto').click()">
                <div class="text-3xl mb-2"><i class="fa-solid fa-camera"></i></div>
                <p>Toque para adicionar uma foto</p>
                <p class="text-xs text-gray-400">JPG, PNG — máx. 5MB</p>
            </div>
            <input type="file" id="foto" name="foto" accept="image/*" class="hidden" onchange="previewFoto(this)">
            <img id="foto-preview" class="hidden w-full max-h-56 object-cover rounded-lg mt-2">
        </div>
    </div>

    <button type="submit" class="block w-full bg-azul text-white py-3 rounded-lg font-semibold mb-20 hover:opacity-90 hover:shadow-md active:scale-[.98] focus-visible:outline focus-visible:outline-2 focus-visible:outline-amarelo focus-visible:outline-offset-2 transition">
        <i class="fa-solid fa-paper-plane"></i> Enviar Registro
    </button>
</form>
@endsection

@section('scripts')
<script>
    // Mapa Leaflet
    const lat0 = {{ old('lat', -25.4290) }};
    const lng0 = {{ old('lng', -49.2671) }};
    const map = L.map('mapa').setView([lat0, lng0], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);
    // Garante re-medida do container caso o layout assente após a inicialização
    setTimeout(() => map.invalidateSize(), 100);

    let marker = null;
    const latInput   = document.getElementById('lat');
    const lngInput   = document.getElementById('lng');
    const coordInfo  = document.getElementById('coord-info');
    const enderecoEl = document.getElementById('endereco_texto');
    const geoStatus  = document.getElementById('geo-status');

    function setPonto(lat, lng, zoom = 16) {
        latInput.value  = (+lat).toFixed(7);
        lngInput.value  = (+lng).toFixed(7);
        coordInfo.textContent = `Selecionado: ${(+lat).toFixed(5)}, ${(+lng).toFixed(5)}`;
        if (marker) map.removeLayer(marker);
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], zoom);
    }

    @if(old('lat'))
        setPonto({{ old('lat') }}, {{ old('lng') }}, 14);
    @endif

    // Clique no mapa = ajuste fino
    map.on('click', e => setPonto(e.latlng.lat, e.latlng.lng, map.getZoom()));

    // ─── Geocoding via Nominatim (OSM, sem API key) ───
    let geoTimer = null;
    let geoCtrl  = null;

    enderecoEl.addEventListener('input', () => {
        clearTimeout(geoTimer);
        if (geoCtrl) geoCtrl.abort();
        const q = enderecoEl.value.trim();
        if (q.length < 4) { geoStatus.textContent = ''; return; }

        geoStatus.textContent = '';
        geoTimer = setTimeout(() => buscarEndereco(q), 600);
    });

    async function buscarEndereco(q) {
        geoStatus.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Buscando...';
        geoStatus.className = 'ml-2 text-xs font-normal text-gray-500';
        geoCtrl = new AbortController();
        try {
            const url = `https://nominatim.openstreetmap.org/search?format=json&limit=1&countrycodes=br&accept-language=pt-BR&q=${encodeURIComponent(q)}`;
            const res = await fetch(url, {
                signal: geoCtrl.signal,
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data && data.length > 0) {
                setPonto(data[0].lat, data[0].lon, 17);
                geoStatus.innerHTML = '<i class="fa-solid fa-check"></i> Endereço encontrado';
                geoStatus.className = 'ml-2 text-xs font-normal text-verde';
            } else {
                geoStatus.innerHTML = '<i class="fa-solid fa-circle-exclamation"></i> Endereço não encontrado — clique no mapa para marcar';
                geoStatus.className = 'ml-2 text-xs font-normal text-laranja';
            }
        } catch (err) {
            if (err.name !== 'AbortError') {
                geoStatus.innerHTML = '<i class="fa-solid fa-triangle-exclamation"></i> Erro ao buscar endereço';
                geoStatus.className = 'ml-2 text-xs font-normal text-red-600';
            }
        }
    }

    // Preview de foto
    function previewFoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const img = document.getElementById('foto-preview');
                img.src = e.target.result;
                img.classList.remove('hidden');
                document.getElementById('upload-area').classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
