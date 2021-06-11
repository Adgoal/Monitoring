FROM php:7.3-cli-alpine

RUN apk add git unzip autoconf gcc make g++ zlib-dev

# -------------------- Installing PHP Extension: sockets --------------------
RUN set -eux \
    # Installation: Generic
    # Type:         Built-in extension
    && docker-php-ext-install -j$(getconf _NPROCESSORS_ONLN) sockets \
    && true


COPY php.ini /usr/local/etc/php/conf.d/default-php.ini
