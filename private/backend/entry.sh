#!/bin/bash

until mysql -h"mysql" -u"upb" -p"upb" -e "quit" >/dev/null 2>&1; do
    echo "Waiting for MySQL to be available..."
    sleep 1
done

if [ ! -f /var/www/html/public/migration.temp ]; then
    php artisan migrate:fresh --force
    php artisan db:seed --force
    touch /var/www/html/public/migration.temp
    php artisan key:generate
    php artisan jwt:secret
fi
