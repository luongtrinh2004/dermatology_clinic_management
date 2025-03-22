# Sử dụng image php-fpm làm base
FROM php:8.1-fpm

# Cài đặt các gói hệ thống cần thiết và extension PHP
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    git \
    curl \
    libonig-dev

# Cài đặt các extension PHP cần thiết cho Laravel (pdo_mysql, mbstring, bcmath, gd)
RUN docker-php-ext-install pdo_mysql mbstring bcmath gd

# Cài đặt Composer từ image chính thức (multi-stage build)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc trong container
WORKDIR /var/www

# Copy toàn bộ mã nguồn của dự án (bao gồm file artisan, composer.json, composer.lock, ...) vào container
COPY . .

# Cài đặt các package PHP, lúc này file artisan đã có sẵn để chạy các script post-install của Laravel
RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Tạo autoload cho Composer (tùy chọn, nếu cần tối ưu)
RUN composer dump-autoload --optimize

# Cấp quyền ghi cho các thư mục cần thiết (storage và bootstrap/cache)
RUN chown -R www-data:www-data storage bootstrap/cache && chmod -R 775 storage bootstrap/cache

# Expose cổng mặc định của php-fpm (9000)
EXPOSE 9000

# Khởi chạy php-fpm khi container khởi động
CMD ["php-fpm"]
