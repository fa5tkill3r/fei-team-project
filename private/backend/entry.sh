#!/bin/bash

if [ ! -f /var/www/migration.temp ]; then
    php artisan migrate:fresh --force
    php artisan db:seed --force
    php artisan key:generate
    php artisan jwt:secret
    touch /var/www/migration.temp
else
    php artisan migrate
fi

php-fpm
