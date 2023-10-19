FROM php:8-alpine

RUN apk --no-cache update && \
    apk --no-cache add git zip unzip curl libzip-dev && \
    docker-php-ext-install mysqli pdo_mysql 

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    
# Set the working directory to your web application's directory
WORKDIR /var/www

# Copy composer.json and composer.lock (if available)
COPY composer.json composer.json
COPY composer.lock composer.lock

# Install project dependencies
RUN composer install --no-scripts --no-autoloader --no-dev

# Copy your PHP application files into the container (assuming your PHP files are in the same directory as the Dockerfile)
COPY . /var/www

# Finish Composer
RUN composer dump-autoload --optimize

# Expose port 80 (the default Apache port)
EXPOSE 80

# Start the Apache web server
# CMD ["php -S 0.0.0.0:80 -t public"]
