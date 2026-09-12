FROM php:8.2-fpm-alpine

RUN apk update && apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    linux-headers \
    $PHPIZE_DEPS \
    && docker-php-ext-install pdo_mysql opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/tp8-admin

COPY tp8-admin/composer.json ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader || true

COPY tp8-admin/ .

EXPOSE 8001

CMD ["php", "think", "run", "-H", "0.0.0.0", "-p", "8001"]
