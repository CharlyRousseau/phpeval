FROM php:8.1-apache

# Enable mod_rewrite
RUN a2enmod rewrite

# Install dependencies for Composer and PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    && docker-php-ext-install zip pdo_mysql\
    && docker-php-ext-install mysqli \
    && docker-php-ext-enable mysqli

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy your custom apache2.conf
COPY apache2.conf /etc/apache2/apache2.conf

# Only for deployment
# Copy your application code into the image
COPY . /var/www/html

# Set appropriate permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html
