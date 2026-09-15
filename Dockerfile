FROM php:8.2-cli-alpine

RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    oniguruma-dev \
    sqlite-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring bcmath

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

COPY . /var/www

EXPOSE 8000

CMD php artisan serve --host=0.0.0.0 --port=8000

