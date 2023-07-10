FROM php:8.1-fpm-alpine

# Copy composer.lock and composer.json
COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
#RUN apt-get update && apt-get install -y \
RUN apk update
RUN apk add --no-cache build-base
RUN apk add libpng-dev
RUN apk add --no-cache libjpeg-turbo-dev
RUN apk add --no-cache freetype-dev
RUN apk add zip
RUN apk add jpegoptim optipng pngquant gifsicle
RUN apk add vim
RUN apk add unzip
RUN apk add git
RUN apk add curl
RUN apk add libzip-dev
RUN apk add --no-cache oniguruma-dev

RUN apk add --no-cache tzdata \
    && apk add --no-cache --virtual .locale-deps gettext \
    && cp /usr/bin/envsubst /usr/local/bin/envsubst \
    && echo "en_US.UTF-8 UTF-8" > /etc/locale.gen \
    && apk add --no-cache --virtual .locale-rundeps \
       $(apk search --no-cache --quiet locale-\*-utf8) \
    && apk del .locale-deps \
    && ln -sf /usr/share/zoneinfo/UTC /etc/localtime \
    && echo "UTC" > /etc/timezone

# Install extensions
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install gd
RUN apk add --no-cache shadow

# Install phpredis
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS && \
    pecl update-channels && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    apk del $PHPIZE_DEPS

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apk add --no-cache \
    autoconf \
    make \
    gcc \
    g++

RUN apk add --update linux-headers

#install xdebug TODO REMOVE TO DEV FILE
RUN pecl install xdebug-3.2.1
RUN docker-php-ext-enable xdebug
ENV PHP_IDE_CONFIG 'serverName=dockerServer'

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000

COPY --chown=www:www ./docker/php/run.sh /tmp
RUN chmod +x /tmp/run.sh
ENTRYPOINT ["/tmp/run.sh"]
