FROM php:8.2-apache

# Installer les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpq-dev \
    postgresql-client \
    && docker-php-ext-install pdo pdo_pgsql

# Activer les modules Apache nécessaires
RUN a2enmod rewrite expires deflate headers filter

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier la configuration Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Copier le .htaccess et les fichiers du projet
COPY . /var/www/html/

# Donner les bonnes permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod 644 /var/www/html/.htaccess

EXPOSE 80

CMD ["apache2-foreground"]
