#!/bin/sh
set -e

envsubst < /usr/local/etc/php/conf.d/php.ini.template > /usr/local/etc/php/conf.d/php.ini

composer dump-autoload --optimize

for i in $(seq 1 10); do
  if php artisan migrate:status >/dev/null 2>&1; then
    break
  fi
  echo "Waiting for DB... ($i)"
  sleep 1
done

if ! grep -q '^APP_KEY=.\+' .env; then
  echo "Generating application key..."
  php artisan key:generate
  php artisan config:cache
  export $(grep ^APP_KEY= .env)
fi

php artisan migrate --force --no-interaction
php artisan optimize

php-fpm
