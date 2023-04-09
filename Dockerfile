FROM php:8.2-cli

RUN apt-get update && \
    apt-get install -y \
    default-mysql-client \
    libxml2-dev \
    && docker-php-ext-install \
    pdo_mysql \
    xml


# Copy application files
WORKDIR /app
COPY . .


# Set permissions for storage folder
RUN chown -R www-data:www-data storage

# Install composer and dependencies
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install

# Healthcheck for MySQL service
HEALTHCHECK --interval=5s \
    --timeout=3s \
    CMD mysqladmin ping -h db -u root -p${MYSQL_ROOT_PASSWORD} || exit 1

# Wait for MySQL service to be ready and then run the commands
CMD ["sh", "-c", "while ! mysqladmin ping -h db --silent; do sleep 1; done; php datafeeder migrate; php datafeeder test; php datafeeder import:products /data/feed.xml"]
