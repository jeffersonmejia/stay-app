FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libssh2-1-dev \
    libssh2-1 \
    libssl-dev \
    unzip \
    && pecl install ssh2-1.4.2 \
    && docker-php-ext-enable ssh2 \
    && docker-php-ext-install mysqli pdo pdo_mysql ftp \
    && a2enmod rewrite \
    && sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf


COPY ./public/ /var/www/html/
COPY ./server/ /var/www/html/server/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
