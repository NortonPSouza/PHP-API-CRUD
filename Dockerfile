FROM php:8.2-cli
RUN apt update && apt install -y unzip
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
WORKDIR /var/www/code
COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader
COPY . .
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]