# Usar la imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias del sistema para las extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip

# Habilitar el mod_rewrite de Apache
RUN a2enmod rewrite

# Copiar el código fuente de la aplicación al contenedor
WORKDIR /var/www/html
COPY . .

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader

# Exponer el puerto 80
EXPOSE 8000

# Configurar el entrypoint de Apache
CMD ["apache2-foreground"]
