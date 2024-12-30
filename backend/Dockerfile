FROM php:8.3

RUN apt-get update -y && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl

RUN docker-php-ext-install pdo mbstring pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /app/backend

COPY . .

RUN composer install

EXPOSE 8000

CMD php artisan migrate:fresh --force --seed && php artisan key:generate && php artisan serve --host=0.0.0.0 --port=8000
