FROM php:8.3-fpm

# Install system dependencies  -  for the LARAVEL PROJECT container ONLY
RUN apt-get update \
    && apt-get install -y libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libonig-dev zlib1g-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*
    #
    # i.e. = 'apt-get clean && rm -rf /var/lib/apt/lists/*' 
    # I added it to be executed after installing system dependencies, to yield a docker image with smaller size
    # REASON :- to reduce the resulting DOCKER IMAGE size.



# Install PHP extensions  -  for the LARAVEL PROJECT container ONLY
RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
    && docker-php-ext-install pdo_mysql gd mbstring zip exif \
    && docker-php-ext-enable pdo_mysql gd mbstring zip exif

# Install Redis extension  -  for the LARAVEL PROJECT container ONLY
RUN pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && docker-php-ext-enable redis

# Install Composer  -  for the LARAVEL PROJECT container ONLY
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory  -  for the LARAVEL PROJECT container ONLY
WORKDIR /var/www/html

# Copy the Laravel project to the container  -  for the LARAVEL PROJECT container ONLY
COPY . /var/www/html

# Copy the composer.json and composer.lock files to the container  -  for the LARAVEL PROJECT container ONLY
COPY composer.json composer.lock /var/www/html/

# Install the project dependencies using Composer  -  for the LARAVEL PROJECT container ONLY
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Set the file permissions  -  for the LARAVEL PROJECT container ONLY
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy the Nginx configuration file to the container  -  for the LARAVEL PROJECT container ONLY
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Start the PHP-FPM server  -  for the LARAVEL PROJECT container ONLY
CMD ["php-fpm", "-F"]


# NOTE to self - 
#
# abrham remember/do NOT forget
#
# the configuration you see above is ONLY for   -  for the LARAVEL PROJECT container      , it is NOT for the NGiNX or MySQL Container
#
# so any operation done above is done for ONLY LARAVEL PROJECT container,   NOT for the Nginx or MySQL Container
#
# ANY directories COPiED hare are ONLY found in Laravel Container
# ANY inatallation done and permissions set above is only done in Laravel Container 
#
# if you want to do the above commands for Mysql or Nginx container, CREATE a SEPARATE Dockerfile for NGiNX or MySQL 
#
