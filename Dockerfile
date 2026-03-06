# Usamos una imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalamos las extensiones necesarias para conectar con MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# COPIA TODO EL CONTENIDO DEL REPO A LA RAÍZ DEL SERVIDOR
# El punto "." significa "todo lo que hay en mi carpeta actual"
COPY . /var/www/html/

# Aseguramos que los permisos sean correctos para Apache
RUN chown -R www-data:www-data /var/www/html

# Le decimos a Apache que escuche en el puerto 80
EXPOSE 80