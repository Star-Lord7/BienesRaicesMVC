# Usar la imagen oficial de PHP 8.4 con CLI
FROM php:8.4-cli

# Instalar mysqli y extensiones necesarias
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli

# Copiar todo el proyecto al contenedor
WORKDIR /app
COPY . /app

# Exponer el puerto 8080 para Railway
EXPOSE 8080

# Comando para iniciar el servidor PHP
CMD ["php", "-S", "0.0.0.0:8080", "-t", "public"]