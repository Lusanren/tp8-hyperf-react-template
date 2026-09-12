FROM hyperf/hyperf:8.2-alpine-v3.18-swoole

WORKDIR /opt/www

COPY hyperf-service/composer.json ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader || true

COPY hyperf-service/ .

EXPOSE 9501

CMD ["php", "/opt/www/bin/hyperf.php", "start"]
