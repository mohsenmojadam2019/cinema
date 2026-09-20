FROM php:8.3-cli
RUN apt-get update && apt-get install -y git unzip libzip-dev libpng-dev libonig-dev && docker-php-ext-install pdo_sqlite mbstring zip
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY . .
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN cp .env.example .env && php artisan key:generate
EXPOSE 8000
CMD ["sh","-c","touch database/database.sqlite && php artisan migrate --force --seed && php artisan serve --host=0.0.0.0 --port=8000"]
