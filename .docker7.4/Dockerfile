FROM php:7.4.29-apache

RUN apt-get clean all
RUN apt-get update
RUN apt-get dist-upgrade -y

RUN apt-get install -y wget zip libcurl4-openssl-dev libpq-dev libzip-dev libjpeg62-turbo-dev libpng-dev libfreetype6-dev libonig-dev libapache2-mod-xsendfile jpegoptim optipng pngquant webp npm gifsicle pkg-config imagemagick libmpdec-dev libmemcached-dev

RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN pecl install redis

RUN echo "extension=redis.so" > /usr/local/etc/php/conf.d/10_redis.ini

RUN pecl install memcached

RUN echo "extension=memcached.so" > /usr/local/etc/php/conf.d/20_memcached.ini

RUN pecl install decimal

RUN echo "extension=decimal.so" > /usr/local/etc/php/conf.d/30_decimal.ini

RUN pecl install imagick

RUN echo "extension=imagick.so" > /usr/local/etc/php/conf.d/40_imagick.ini

RUN docker-php-ext-install pdo_mysql mbstring zip exif intl soap xml gd curl mysqli calendar

RUN npm install -g svgo

RUN mkdir -p /tmp/pear/cache

RUN mkdir /app

RUN chown -R www-data:www-data /app && a2enmod rewrite