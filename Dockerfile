FROM php:8-apache

RUN apt-get update \
 && apt-get install --assume-yes \
      7zip
RUN docker-php-ext-install mysqli
RUN a2enmod rewrite headers expires

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
