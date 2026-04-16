{{-- Shared head: Tailwind Play CDN with custom theme + FontAwesome + Leaflet --}}
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    azul: '#1565C0',
                    'azul-dark': '#0D47A1',
                    amarelo: '#FDD835',
                    verde: '#2E7D32',
                    laranja: '#E65100',
                    texto: '#212121',
                    borda: '#E0E0E0',
                    'pend-bg': '#FFF9C4', 'pend-fg': '#F57F17',
                    'and-bg':  '#E3F2FD', 'and-fg':  '#1565C0',
                    'res-bg':  '#E8F5E9', 'res-fg':  '#2E7D32',
                },
                fontFamily: { sans: ['Segoe UI', 'system-ui', 'sans-serif'] },
                boxShadow: {
                    card:        '0 2px 8px rgba(0,0,0,.12)',
                    'card-hover':'0 4px 16px rgba(0,0,0,.15)',
                    hero:        '0 4px 20px rgba(13,71,161,.25)',
                    auth:        '0 8px 32px rgba(0,0,0,.2)',
                    'btm-nav':   '0 -2px 8px rgba(0,0,0,.08)',
                },
            }
        }
    }
</script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
