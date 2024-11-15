FROM php:8.2.7-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    && docker-php-ext-install zip pdo pdo_pgsql

# Instala git
RUN ACCEPT_EULA=Y apt-get install -y git

# Instala o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache modules
RUN a2enmod rewrite

# Copy the application code
COPY . /var/www/html

# Set the working directory
WORKDIR /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage

# Copy Apache vhost configuration
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Expose the port
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
