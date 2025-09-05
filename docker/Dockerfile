FROM php:8.2-fpm

RUN find /etc/apt/ -name "*.list" -exec sed -i 's|http://deb.debian.org|https://deb.debian.org|g' {} \; && \
    apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    nano \
    libzip-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

WORKDIR /var/www

COPY . /var/www

RUN chown -R www-data:www-data /var/www && chmod -R 755 /var/www

EXPOSE 9000

CMD ["php-fpm"]
