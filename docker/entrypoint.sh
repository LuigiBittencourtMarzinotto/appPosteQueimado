#!/bin/sh
set -e

cd /var/www

echo "================================================"
echo "  Poste Queimado - Iniciando..."
echo "================================================"

# ------------------------------------------------------------------
# .env
# No Railway o arquivo nao existe (esta no .gitignore), entao ele e
# gerado a partir das variaveis cadastradas no painel. Localmente o
# arquivo ja veio no build e e mantido como esta.
# ------------------------------------------------------------------
if [ ! -f .env ]; then
    printenv | awk -F= '
        /^(APP_|DB_|LOG_|CACHE_|SESSION_|FILESYSTEM_|MAIL_|QUEUE_|REDIS_)/ {
            key = $1
            sub(/^[^=]*=/, "", $0)
            gsub(/"/, "\\\"", $0)
            print key "=\"" $0 "\""
        }' > .env
    echo "OK: .env gerado a partir do ambiente"
fi

# Gerar chave se vazia
KEY=$(grep '^APP_KEY=' .env | cut -d'=' -f2- | tr -d '"')
if [ -z "$KEY" ]; then
    php artisan key:generate --force
    echo "AVISO: APP_KEY gerada no boot - defina APP_KEY nas variaveis"
    echo "       para nao invalidar as sessoes a cada deploy"
fi

# ------------------------------------------------------------------
# Aguardar banco (credenciais vindas do ambiente, com fallback local)
# ------------------------------------------------------------------
echo "Aguardando banco MySQL em ${DB_HOST:-db}:${DB_PORT:-3306}..."
TRIES=0
until php -r '
    $h = getenv("DB_HOST")     ?: "db";
    $p = getenv("DB_PORT")     ?: "3306";
    $d = getenv("DB_DATABASE") ?: "poste_queimado";
    $u = getenv("DB_USERNAME") ?: "laravel";
    $w = getenv("DB_PASSWORD") ?: "secret";
    try { new PDO("mysql:host=$h;port=$p;dbname=$d", $u, $w); exit(0); }
    catch (Exception $e) { exit(1); }
' 2>/dev/null; do
    TRIES=$((TRIES + 1))
    if [ "$TRIES" -ge 40 ]; then
        echo "ERRO: banco nao respondeu apos 40 tentativas."
        echo "      Confira DB_HOST / DB_PORT / DB_DATABASE / DB_USERNAME / DB_PASSWORD."
        exit 1
    fi
    echo "  banco nao pronto, tentando em 3s... ($TRIES/40)"
    sleep 3
done
echo "OK: Banco pronto"

# Migrations
php artisan migrate --force
echo "OK: Migrations executadas"

# Seed apenas se users estiver vazio
COUNT=$(php artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | grep -E '^[0-9]+$' | head -1)
if [ "$COUNT" = "0" ] || [ -z "$COUNT" ]; then
    php artisan db:seed --force
    echo "OK: Dados de teste inseridos"
fi

# Storage link
php artisan storage:link --force 2>/dev/null || true
echo "OK: Storage linkado"

php artisan config:clear 2>/dev/null || true

# ------------------------------------------------------------------
# nginx + php-fpm no mesmo container, escutando a porta do ambiente
# ------------------------------------------------------------------
PORT="${PORT:-8080}"
sed "s/__PORT__/$PORT/" /var/www/docker/nginx/app.conf > /etc/nginx/conf.d/default.conf

echo "================================================"
echo "  HTTP na porta $PORT"
echo "  Usuario: joao@email.com / user123"
echo "  Admin:   admin@postequeimado.com / admin123"
echo "================================================"

php-fpm -D
exec nginx -g 'daemon off;'
