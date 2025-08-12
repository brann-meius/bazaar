#!/bin/sh
set -e

composer dump-autoload --optimize

for i in $(seq 1 10); do
  if php artisan migrate:status >/dev/null 2>&1; then
    break
  fi
  echo "Waiting for DB... ($i)"
  sleep 1
done

PENDING=false
if ! grep -q '^APP_KEY=.\+' .env; then
  echo "Generating application key..."
  php artisan key:generate
  php artisan config:cache
  export $(grep ^APP_KEY= .env)
  PENDING=true
fi

php artisan migrate --force --no-interaction

if [ PENDING ]; then
  echo "Running seeders..."
  php artisan db:seed
fi

php artisan optimize

php-fpm
