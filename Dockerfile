# build a PHP container with the extensions that the Laravel project needs
FROM php:8.1-fpm

# system dependencies required by Laravel or for building packages
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# composer is already available in a separate image, copy it
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# copy entrypoint (optional) if you want to run migrations/seeds etc.
# COPY docker-entrypoint.sh /usr/local/bin/
# RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# default command just starts php-fpm
CMD ["php-fpm"]
