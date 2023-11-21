FROM php:8.2-fpm

WORKDIR /var/www/private

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN apt-get update && apt-get install -y \
       curl \
       libonig-dev \
       libxml2-dev \
       libpq-dev \
       zlib1g-dev \
       zip \
       unzip \
       postgresql-client

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_pgsql mbstring exif pcntl bcmath

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./

RUN composer install --no-scripts --no-autoloader

COPY . .

RUN mv .env.example .env

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data storage bootstrap/cache

#ENV APP_ENV=production

COPY entry.sh .

RUN chmod +x ./entry.sh

CMD ["./entry.sh"]
