FROM dunglas/frankenphp:php8.2

# Install system deps + GD
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    unzip \
    git \
    nodejs \
    npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

COPY . .

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Build frontend (jika ada)
RUN if [ -f package.json ]; then npm install && npm run build || true; fi

# Permission Laravel
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 8080

CMD php artisan serve --host=0.0.0.0 --port=8080
