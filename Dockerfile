# =========================
# Stage 1 - Composer
# =========================
FROM composer:2 AS composer

WORKDIR /app

COPY . .

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction

# =========================
# Stage 2 - PHP + Apache
# =========================
FROM php:8.2-apache

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip

# Habilita mod_rewrite do Apache
RUN a2enmod rewrite

# Define diretório da aplicação
WORKDIR /var/www/html

# Copia arquivos do Composer
COPY --from=composer /app /var/www/html

# Configura Apache para usar /public
RUN sed -i 's!/var/www/html!/var/www/html/public!g' \
    /etc/apache2/sites-available/000-default.conf

# Permissões Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Expõe porta HTTP
EXPOSE 80

# Inicializa Apache
CMD ["apache2-foreground"]