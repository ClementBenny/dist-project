FROM php:8.3-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip curl libsqlite3-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy project
COPY . .

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_sqlite

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Create SQLite DB if not exists
RUN mkdir -p database && touch database/database.sqlite

# Set permissions (basic)
RUN chmod -R 775 storage bootstrap/cache

# Expose port
EXPOSE 10000

# Start Laravel server
CMD php artisan serve --host=0.0.0.0 --port=10000