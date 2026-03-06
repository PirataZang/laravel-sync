FROM php:8.5-cli

WORKDIR /var/www/html

# =========================
# Dependências do sistema
# =========================
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpq-dev \
    libzip-dev \
    gnupg \
    ca-certificates \
    build-essential \
    nano

# =========================
# Extensões PHP
# =========================
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip

# =========================
# Redis extension (phpredis)
# =========================
RUN pecl install redis \
    && docker-php-ext-enable redis

# =========================
# Node.js 22 LTS
# =========================
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# =========================
# Composer
# =========================
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =========================
# Permissões
# =========================
RUN chown -R www-data:www-data /var/www/html

# =========================
# Limpeza
# =========================
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# =========================
# Portas
# =========================
EXPOSE 8000
EXPOSE 5173

# =========================
# Start Laravel
# =========================
CMD php artisan serve --host=0.0.0.0 --port=8000