FROM php:8.2-cli-alpine

# Install system dependencies and required PHP extensions
RUN apk add --no-cache \
        libzip-dev \
        unzip \
        git \
    && docker-php-ext-install zip pcntl posix pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy the source code into the container
COPY . .

# Expose both ports (Symfony web server and Workerman)
EXPOSE 8000
EXPOSE 2346