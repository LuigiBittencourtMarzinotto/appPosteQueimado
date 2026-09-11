{{-- ==========================================================
     Shared head — design tokens (Tailwind Play CDN) + assets
     Fonte: Inter · Ícones: FontAwesome · Mapa: Leaflet
     ========================================================== --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">

<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    /* ── Marca (escala completa em torno do azul institucional) ── */
                    brand: {
                        50:  '#EFF6FF', 100: '#DBEAFE', 200: '#BFDBFE', 300: '#93C5FD',
                        400: '#4A90D9', 500: '#1E75CE', 600: '#1565C0', 700: '#0D47A1',
                        800: '#0A3880', 900: '#082B62',
                    },
                    /* ── Aliases legados (mantidos p/ compatibilidade) ── */
                    azul: '#1565C0',
                    'azul-dark': '#0D47A1',
                    amarelo: '#FDD835',
                    verde: '#2E7D32',
                    laranja: '#E65100',
                    texto: '#1A2233',
                    borda: '#E3E8EF',
                    /* ── Superfícies e texto ── */
                    surface: '#FFFFFF',
                    canvas:  '#F5F7FA',
                    ink:     { DEFAULT: '#1A2233', soft: '#4B5768', muted: '#7A8699' },
                    line:    { DEFAULT: '#E3E8EF', soft: '#EEF1F6' },
                    /* ── Status (bg / fg / borda) ── */
                    'pend-bg': '#FFF8E1', 'pend-fg': '#B45309', 'pend-dot': '#F59E0B',
                    'and-bg':  '#E8F1FC', 'and-fg':  '#1565C0', 'and-dot':  '#2563EB',
                    'res-bg':  '#E7F6EA', 'res-fg':  '#1B6E32', 'res-dot':  '#16A34A',
                },
                fontFamily: {
                    sans: ['Inter', 'Segoe UI', 'system-ui', '-apple-system', 'sans-serif'],
                },
                borderRadius: { '4xl': '1.75rem' },
                boxShadow: {
                    xs:           '0 1px 2px rgba(16,24,40,.05)',
                    card:         '0 1px 3px rgba(16,24,40,.06), 0 1px 2px rgba(16,24,40,.04)',
                    'card-hover': '0 8px 24px -6px rgba(16,24,40,.14), 0 2px 6px rgba(16,24,40,.06)',
                    hero:         '0 12px 32px -8px rgba(13,71,161,.45)',
                    auth:         '0 24px 60px -20px rgba(8,43,98,.35)',
                    'btm-nav':    '0 -1px 3px rgba(16,24,40,.06)',
                    pop:          '0 12px 32px -8px rgba(16,24,40,.2), 0 0 0 1px rgba(16,24,40,.05)',
                },
                keyframes: {
                    'fade-up':  { '0%': { opacity: 0, transform: 'translateY(8px)' },  '100%': { opacity: 1, transform: 'none' } },
                    'fade-in':  { '0%': { opacity: 0 }, '100%': { opacity: 1 } },
                    'slide-in': { '0%': { opacity: 0, transform: 'translateY(-12px)' }, '100%': { opacity: 1, transform: 'none' } },
                    'pop-in':   { '0%': { opacity: 0, transform: 'scale(.96)' }, '100%': { opacity: 1, transform: 'none' } },
                },
                animation: {
                    'fade-up':  'fade-up .35s cubic-bezier(.16,1,.3,1) both',
                    'fade-in':  'fade-in .25s ease-out both',
                    'slide-in': 'slide-in .3s cubic-bezier(.16,1,.3,1) both',
                    'pop-in':   'pop-in .2s cubic-bezier(.16,1,.3,1) both',
                },
            }
        }
    }
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
