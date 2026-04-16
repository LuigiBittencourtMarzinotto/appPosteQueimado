FROM php:8.2-fpm

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev \
    libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copiar projeto completo
COPY . .

# Instalar dependências PHP (composer install)
RUN composer install --optimize-autoloader --no-interaction

# Permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage \
    && chmod -R 775 /var/www/bootstrap/cache

# Entrypoint
RUN chmod +x /var/www/docker/entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/var/www/docker/entrypoint.sh"]
