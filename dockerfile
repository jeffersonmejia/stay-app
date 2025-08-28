#IMAGE PHP + APACHE
FROM php:8.2-apache

# INSTALL EXTENSIONS
RUN docker-php-ext-install mysqli pdo pdo_mysql

#COPY FILES AND DIRECTORIES
COPY ./public/ /var/www/html/
COPY ./server/ /var/www/html/server/

# SETUP PERMISSIONS
RUN chown -R www-data:www-data /var/www/html

# ENABLE REWRITE
RUN a2enmod rewrite

# ALLOW .HTACCESS OVERRIDE
RUN sed -i 's/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# PORT SERVER EXPOSE
EXPOSE 80

#START APACHE SV
CMD ["apache2-foreground"]