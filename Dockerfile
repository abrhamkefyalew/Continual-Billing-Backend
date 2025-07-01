# Use Alpine-based PHP 8.3 FPM image // smaller image size
FROM php:8.3-fpm-alpine


# Install system dependencies  -  for the LARAVEL PROJECT container ONLY
RUN apk update \
    && apk add --no-cache \
        libzip-dev \
        libpng-dev \
        libjpeg-turbo-dev \
        freetype-dev \
        oniguruma-dev \
        zlib-dev \
        curl \
        autoconf \
        gcc \
        g++ \
        make \
        libtool \
        re2c \
        zip \                     
        unzip \                   
        dos2unix \                
        supervisor \
        file 
    #
    # i.e. = 'apk add --no-cache ...'
    # We use '--no-cache' to reduce image size by avoiding persistent package index files


# Install PHP extensions and PECL extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd mbstring zip exif opcache \
    && docker-php-ext-enable pdo_mysql gd mbstring zip exif opcache \
    #
    # I added 'opcache' installation to improve Laravel performance in production
    #
    && pecl install -o -f redis igbinary \
    && docker-php-ext-enable redis igbinary


# Remove compiler tools after building to reduce image size
RUN apk del autoconf gcc g++ make libtool re2c \
    && rm -rf /tmp/pear /var/cache/apk/* /var/tmp/*
    #
    # Clean up after PECL and system dependencies
    #
    # I added this to clean up cache after installing system dependencies
    # REASON :- to reduce the resulting DOCKER IMAGE size.


# Install Composer  -  for the LARAVEL PROJECT container ONLY
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && rm -f composer-setup.php
#
# This installs Composer globally as 'composer' command


# Set the working directory  -  for the LARAVEL PROJECT container ONLY
WORKDIR /var/www/html


# Copy the Laravel project to the container  -  for the LARAVEL PROJECT container ONLY
COPY . /var/www/html
#
# NOTE: This already includes composer.json and composer.lock,
# so no need to COPY them again separately.


# Install the project dependencies using Composer  -  for the LARAVEL PROJECT container ONLY
RUN php /usr/local/bin/composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev
#
# --no-dev: skips dev dependencies (e.g., PHPUnit, IDE helpers) to reduce image size
# --optimize-autoloader: optimizes PSR autoloading for production


# Set the file permissions  -  for the LARAVEL PROJECT container ONLY
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
#
# Make sure Laravel can write to storage and cache directories


# Copy the Nginx configuration file to the container  -  for the LARAVEL PROJECT container ONLY
# COPY nginx.conf /etc/nginx/conf.d/default.conf
#
# Removed above line because Nginx config belongs to the NGINX container,
# and is already mounted via docker-compose.yml


# Start the PHP-FPM server  -  for the LARAVEL PROJECT container ONLY
CMD ["php-fpm", "-F"]
#
# Start PHP-FPM in the foreground so Docker keeps the container running
