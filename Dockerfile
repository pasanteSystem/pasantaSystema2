# Usamos una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos las extensiones necesarias para conectar con MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiamos todos tus archivos del repositorio a la carpeta del servidor
COPY . /var/www/html/

# Le decimos a Apache que escuche en el puerto 80
EXPOSE 80
