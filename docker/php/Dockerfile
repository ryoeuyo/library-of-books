FROM php:8.2-fpm-alpine3.21

ARG PUID=1000
ARG PGID=1000

RUN apk add --no-cache shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

USER www-data

RUN apk add --no-cache \
    autoconf \
    libtool \
    linux-headers \
    bash \
    git \
    curl \
    libzip-dev \
    oniguruma-dev \
    icu-dev \
    build-base
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/symfony
WORKDIR /var/www/symfony

ARG INSTALL_XDEBUG=false
RUN if [ ${INSTALL_XDEBUG} = true ]; \
    then \
    pecl install xdebug && docker-php-ext-enable xdebug; \
    fi;

COPY xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer require --dev phpunit/phpunit:^10.3 friendsofphp/php-cs-fixer:^3.50 symfony/test-pack