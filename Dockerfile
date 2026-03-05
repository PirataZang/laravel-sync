FROM php:8.5-cli

WORKDIR /var/www/html

# Dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    gnupg \
    ca-certificates

# Extensões PHP essenciais
RUN docker-php-ext-install pdo pdo_pgsql zip

# Redis extension (phpredis)
RUN pecl install redis \
    && docker-php-ext-enable redis

# =========================
# Node 22 (mais recente LTS)
# =========================
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Composer oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Limpar cache apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

EXPOSE 8000 5173

CMD php artisan serve --host=0.0.0.0 --port=8000