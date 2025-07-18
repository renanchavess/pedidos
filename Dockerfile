FROM php:7.4-apache

# Instala extensões necessárias
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo_mysql

# Cria a pasta de sessões e configura permissões
RUN mkdir -p /var/www/html/sessions && chmod 755 /var/www/html/sessions

# Habilita o módulo rewrite do Apache
RUN a2enmod rewrite