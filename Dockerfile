# PHP 8.2 FPM
FROM php:8.2-fpm

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Instalar dependências do sistema e extensões necessárias do PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev libonig-dev libxml2-dev zip unzip curl git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring exif bcmath gd \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar apenas arquivos necessários para instalar dependências primeiro (cache melhor)
COPY composer.json composer.lock ./

# Instalar dependências do Laravel
RUN composer install --no-interaction --no-scripts --no-progress --optimize-autoloader

# Copiar restante do código
COPY . .

# Ajustar permissões para storage e cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expor a porta do PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]