FROM php:7.4-fpm

RUN apt-get clean
RUN apt-get update

RUN apt-get update

RUN apt-get install -y --no-install-recommends \
    zip \
    unzip \
    curl \
    nano \
    libzip-dev \
    libpq-dev

RUN docker-php-ext-install pdo pdo_pgsql pgsql zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html
