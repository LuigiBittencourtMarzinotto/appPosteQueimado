#!/bin/sh
set -e

echo "================================================"
echo "  Poste Queimado - Iniciando..."
echo "================================================"

# Gerar chave se vazia
KEY=$(grep "APP_KEY=" /var/www/.env | cut -d'=' -f2)
if [ -z "$KEY" ]; then
    php artisan key:generate --force
    echo "OK: Chave gerada"
fi

# Aguardar MySQL
echo "Aguardando banco MySQL..."
until php -r "try{new PDO('mysql:host=db;dbname=poste_queimado','laravel','secret');exit(0);}catch(Exception \$e){exit(1);}" 2>/dev/null; do
    echo "  banco nao pronto, tentando em 3s..."
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

echo "================================================"
echo "  Acesse: http://localhost:8000"
echo "  Usuario: joao@email.com / user123"
echo "  Admin:   admin@postequeimado.com / admin123"
echo "  phpMyAdmin: http://localhost:8080"
echo "================================================"

exec php-fpm
