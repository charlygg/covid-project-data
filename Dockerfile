FROM php:7.4.9-apache

ENV DEBIAN_FRONTEND noninteractive

ENV DEBCONF_NONINTERACTIVE_SEEN true

RUN docker-php-ext-install  mysqli \
    pdo \
    pdo_mysql

# Modifying Apache for Laravel App
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite

#Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
