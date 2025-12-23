FROM dunglas/frankenphp:php8.2

# Install system deps + GD + tools
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && rm -rf /var/lib/apt/lists/*

# Install Composer (INI KUNCI)
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin \
    --filename=composer

WORKDIR /app

COPY . .

# Install PHP dependencies (vendor)
RUN composer install --no-dev --optimize-autoloader

# Permission Laravel
RUN chmod -R 777 storage bootstrap/cache

# Railway wajib pakai $PORT
CMD php artisan serve --host=0.0.0.0 --port=$PORT
