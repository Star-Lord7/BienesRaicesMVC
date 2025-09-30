# Usar PHP 8.4 CLI
FROM php:8.4-cli

# Instalar mysqli
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

# Instalar dependencias para Composer
RUN apt-get update && apt-get install -y unzip curl

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Crear carpeta de trabajo y copiar el proyecto
WORKDIR /app
COPY . /app

# Instalar dependencias de PHP
RUN composer install --no-dev --optimize-autoloader

# Exponer puerto 8080
EXPOSE 8080

# Iniciar servidor PHP apuntando a public
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]