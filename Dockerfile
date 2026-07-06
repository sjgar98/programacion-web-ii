FROM php:8-apache

RUN apt-get update \
 && apt-get install --assume-yes \
      7zip \
      libfreetype6-dev \
      libjpeg62-turbo-dev \
      libpng-dev
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install mysqli gd
RUN a2enmod rewrite headers expires

COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
