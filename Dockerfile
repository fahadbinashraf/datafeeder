FROM php:8.2-cli

# Install required extensions and dependencies
RUN apt-get update && apt-get install -y \
    default-mysql-client \
    libzip-dev \
    libicu-dev \
    libxml2-dev \
    git \
    && docker-php-ext-install -j$(nproc) \
    intl \
    pdo \
    pdo_mysql \
    zip \
    xml

# Install composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Copy application files
WORKDIR /app
COPY . .
