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

# 🔹 Copiar todos los archivos del proyecto antes de ejecutar Composer
COPY . .

# 🔹 Asegurar que artisan tenga permisos de ejecución
RUN chmod +x /app/artisan

# Instalar dependencias de Composer (sin dependencias de desarrollo)
RUN composer install --no-dev --optimize-autoloader -v

# Copiar .env.example y renombrarlo a .env
RUN cp .env.example /app/.env

# Ejecutar los comandos de Laravel después de la instalación
RUN php artisan config:clear && \
    php artisan route:cache

# Ejecutar la migración de la base de datos
RUN php artisan migrate --force

# Ejecutar seeders
RUN php artisan db:seed --force

# Etapa 2: Imagen base con PHP y Apache
FROM php:8.2-apache

# Definir el directorio de trabajo para Laravel
WORKDIR /var/www/html

# Habilitar módulos de Apache y extensiones PHP necesarias
RUN a2enmod rewrite
RUN a2enmod mpm_prefork
RUN apt-get update && apt-get install -y libzip-dev  # Asegurarse de que libzip-dev esté instalado aquí también
RUN docker-php-ext-install pdo pdo_mysql zip  # Asegurarse de que zip está instalado aquí también

# Configurar Apache para Laravel
COPY .docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Copiar archivos de Laravel desde la etapa anterior
COPY --from=builder /app /var/www/html

# Configurar permisos de los archivos
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer el puerto
EXPOSE 80

# Iniciar Apache en primer plano
CMD ["apache2-foreground"]
