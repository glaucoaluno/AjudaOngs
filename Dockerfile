FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libonig-dev \
    libzip-dev \
  && rm -rf /var/lib/apt/lists/*

# Install PHP extensions used by Laravel
RUN docker-php-ext-install pdo_pgsql mbstring bcmath exif pcntl sockets

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy application files
COPY . /var/www

# Install PHP dependencies for production
RUN composer install --prefer-dist --optimize-autoloader \
  && php artisan config:clear || true \
  && php artisan route:clear || true \
  && php artisan view:clear || true

# Default port used by Render is provided in $PORT
ENV PORT=8000

# Hint Render about the listening port
EXPOSE 8000

# Pre-start tasks and start Laravel using PHP built-in server
CMD ["sh", "-c", "mkdir -p database && touch database/database.sqlite; if [ ! -f .env ]; then cp .env.example .env || true; fi; if [ -z \"$APP_KEY\" ] || [ \"$APP_KEY\" = \"\" ]; then php artisan key:generate --force || true; fi; php artisan storage:link || true; php -d variables_order=EGPCS -S 0.0.0.0:${PORT:-8000} -t public"]

