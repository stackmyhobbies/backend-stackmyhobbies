# ============================
#     STAGE 1 — BUILDER
# ============================
FROM php:8.4-fpm-alpine3.20 AS builder

RUN apk add --no-cache \
    git unzip libzip-dev libpng-dev libjpeg-turbo-dev freetype-dev \
    icu-dev oniguruma-dev postgresql-dev $PHPIZE_DEPS

RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql pgsql intl zip gd bcmath opcache pcntl

RUN printf "\n\n\n\n" | pecl install https://pecl.php.net/get/redis-6.1.0.tgz \
    && docker-php-ext-enable redis

RUN mkdir -p /php-ext \
    && cp -rp /usr/local/lib/php/extensions /php-ext/extensions \
    && cp -rp /usr/local/etc/php/conf.d /php-ext/conf.d

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

COPY . .

# Limpia caché stale de bootstrap antes de package:discover
RUN rm -rf bootstrap/cache/*.php \
    && cp .env.example .env \
    && php artisan package:discover --ansi \
    && rm .env

# ============================
#     STAGE 2 — RUNTIME
# ============================
FROM php:8.4-fpm-alpine3.20

RUN apk add --no-cache \
    libpng libjpeg-turbo freetype libzip icu-libs oniguruma libpq \
    caddy ca-certificates curl netcat-openbsd

WORKDIR /var/www/html
COPY --from=builder /var/www/html ./
COPY --from=builder /php-ext/extensions /usr/local/lib/php/extensions
COPY --from=builder /php-ext/conf.d /usr/local/etc/php/conf.d

COPY ./docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY ./docker/www.conf /usr/local/etc/php-fpm.d/www.conf

RUN addgroup -g 1000 laravel \
    && adduser -G laravel -g laravel -s /bin/sh -D laravel \
    && chown -R laravel:laravel /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

COPY ./docker/Caddyfile /etc/caddy/Caddyfile
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

HEALTHCHECK --interval=30s --timeout=5s --start-period=30s --retries=3 \
    CMD curl -f http://localhost:8080/up || exit 1

ENTRYPOINT ["/entrypoint.sh"]
CMD ["php-fpm"]
