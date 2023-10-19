FROM php:8-alpine

# Install MySQLi extension
RUN docker-php-ext-install mysqli

# Set the working directory to your web application's directory
WORKDIR /var/www

# Copy your PHP application files into the container (assuming your PHP files are in the same directory as the Dockerfile)
COPY . /var/www

# Expose port 80 (the default Apache port)
EXPOSE 80

# Start the Apache web server
# CMD ["php -S 0.0.0.0:80 -t public"]
