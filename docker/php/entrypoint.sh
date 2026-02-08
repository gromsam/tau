#!/bin/sh
set -e

if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

php artisan config:cache
php artisan route:cache
php artisan migrate --force --no-interaction 2>/dev/null || true

exec "$@"
