# syntax=docker/dockerfile:1.7
FROM node:22-alpine AS frontend
WORKDIR /app
COPY package.json pnpm-workspace.yaml ./
COPY pnpm-lock.yaml* ./
RUN corepack enable && pnpm install --frozen-lockfile=false
COPY resources resources
COPY public public
COPY vite.config.ts tsconfig.json components.json ./
RUN pnpm build

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --no-scripts --optimize-autoloader

FROM php:8.4-fpm-alpine AS app
WORKDIR /var/www/html
RUN apk add --no-cache bash curl icu-dev libzip-dev oniguruma-dev supervisor mysql-client \
    && docker-php-ext-install bcmath intl mbstring opcache pcntl pdo_mysql zip \
    && pecl install redis \
    && docker-php-ext-enable redis
RUN addgroup -g 1000 laravel && adduser -D -G laravel -u 1000 laravel
COPY --chown=laravel:laravel . .
COPY --from=vendor --chown=laravel:laravel /app/vendor vendor
COPY --from=frontend --chown=laravel:laravel /app/public/build public/build
COPY docker/prod/php.ini /usr/local/etc/php/conf.d/99-production.ini
COPY docker/prod/entrypoint.sh /usr/local/bin/entrypoint
RUN chmod +x /usr/local/bin/entrypoint && chown -R laravel:laravel storage bootstrap/cache
USER laravel
HEALTHCHECK --interval=30s --timeout=5s --retries=3 CMD php artisan about --only=environment || exit 1
ENTRYPOINT ["entrypoint"]
CMD ["php-fpm"]
