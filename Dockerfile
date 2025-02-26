# Etapa 1: Construcción de dependencias con Composer
FROM php:8.2-cli AS builder

# Instalar Composer manualmente
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

WORKDIR /app

# Copiar archivos de Laravel (excluir node_modules y vendor con .dockerignore)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copiar el resto de la aplicación
COPY . .

# Generar la configuración de Laravel
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:cache

# Etapa 2: Imagen base con PHP y Apache
FROM php:8.2-apache

# Habilitar módulos de Apache y extensiones PHP necesarias
RUN a2enmod rewrite
RUN docker-php-ext-install pdo pdo_mysql zip

# Configurar Apache para Laravel
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar archivos de Laravel desde la etapa anterior
COPY --from=builder /app /var/www/html

# Configurar permisos
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exponer el puerto
EXPOSE 8000

# Iniciar Apache en primer plano
CMD ["apache2-foreground"]
