FROM php:7.0.33-cli
RUN sed -i '/security/d; /updates/d; s|deb.debian.org/debian|archive.debian.org/debian|g' /etc/apt/sources.list \
 && apt-get update && apt-get install -y --allow-unauthenticated git unzip libzip-dev && rm -rf /var/lib/apt/lists/* \
 && docker-php-ext-install zip
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer
