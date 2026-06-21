FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    bash \
    curl \
    git \
    mysql-client \
    postgresql-client \
    unzip \
    zip \
    libzip-dev \
    && docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    zip \
    bcmath \
    opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-interaction --optimize-autoloader --no-dev

RUN cp .env.example .env && \
    php artisan key:generate && \
    php artisan storage:link

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
