# Etapa 1: Construcción de dependencias con Composer
FROM php:8.2-cli AS builder

# Instalar Composer manualmente
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    unzip \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip  # Instalar la extensión zip

WORKDIR /app

# Copiar los archivos de configuración de Composer
COPY composer.json composer.lock ./

# Instalar las dependencias de Composer (sin dependencias de desarrollo)
RUN composer install --no-dev --optimize-autoloader -v

# Copiar el resto de los archivos de la aplicación (esto incluye artisan)
COPY . .

# Asegurarse de que el archivo 'artisan' tiene permisos de ejecución
RUN chmod +x /app/artisan

# Ejecutar los comandos de Laravel después de la instalación
RUN php artisan config:clear && \
    php artisan cache:clear && \
    php artisan route:cache && \
    php artisan package:discover --ansi

# Etapa 2: Imagen base con PHP y Apache
FROM php:8.2-apache

# Habilitar módulos de Apache y extensiones PHP necesarias
RUN a2enmod rewrite
RUN apt-get update && apt-get install -y libzip-dev  # Asegurarse de que libzip-dev esté instalado aquí también
RUN docker-php-ext-install pdo pdo_mysql zip  # Asegurarse de que zip está instalado aquí también

# Configurar Apache para Laravel
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar archivos de Laravel desde la etapa anterior
COPY --from=builder /app /var/www/html

# Configurar permisos de los archivos
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Exponer el puerto
EXPOSE 8000

# Iniciar Apache en primer plano
CMD ["apache2-foreground"]
