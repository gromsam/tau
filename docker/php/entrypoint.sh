#!/bin/sh
set -e

cd /var/www

mkdir -p storage/logs
mkdir -p storage/framework/views
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions

chmod -R 777 storage bootstrap/cache

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

php artisan config:cache
php artisan route:cache
php artisan migrate --force --no-interaction 2>/dev/null || true

exec "$@"
