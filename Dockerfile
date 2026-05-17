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

COPY . .

RUN composer install

CMD ["php-fpm"]


FROM base AS prod

COPY . .

RUN composer install --no-dev --optimize-autoloader

CMD ["php-fpm"]
