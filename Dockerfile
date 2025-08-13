FROM php:8.4-fpm

WORKDIR /var/www/html

RUN apt-get update \
 && apt-get install -y \
      libpq-dev libicu-dev libzip-dev \
      zip unzip git curl \
      build-essential libmemcached-dev gettext-base \
 && docker-php-ext-configure zip \
 && docker-php-ext-install \
      pdo_pgsql \
      zip \
      intl \
      sockets \
 && pecl install memcached \
 && docker-php-ext-enable memcached \
 && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock artisan bootstrap/ ./

RUN composer install --no-autoloader

COPY . .
COPY ./docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

RUN composer dump-autoload --optimize

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000
ENTRYPOINT ["entrypoint.sh"]
