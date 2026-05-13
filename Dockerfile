FROM php:8.4-fpm AS base

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libzip-dev \
    zip \
    default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


FROM base AS dev

RUN pecl install xdebug && docker-php-ext-enable xdebug

CMD ["php-fpm"]


FROM base AS prod

COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
