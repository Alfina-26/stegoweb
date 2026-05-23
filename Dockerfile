FROM php:8.2-apache

# Install ekstensi yang dibutuhkan
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Copy semua file project ke Apache
COPY . /var/www/html/

# Set permission folder upload
RUN mkdir -p /var/www/html/assets/uploads/stego \
    && chmod -R 777 /var/www/html/assets/uploads/stego

# Aktifkan mod_rewrite
RUN a2enmod rewrite

# Expose port
EXPOSE 80