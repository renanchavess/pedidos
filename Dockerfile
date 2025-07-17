# syntax=docker/dockerfile:1

FROM composer:lts as deps

WORKDIR /app

RUN --mount=type=bind,source=composer.json,target=composer.json \
    --mount=type=bind,source=,target=composer.lock \
    --mount=type=cache,target=/tmp/cache \
    composer install --no-dev --no-interaction

FROM php:7.4-apache as final

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --from=deps app/vendor/ /var/www/html/vendor
COPY . /var/www/html

USER www-data
