# Sử dụng hình ảnh `php` chứa PHP 8.1 FPM
FROM php:8.1-fpm

# Cài đặt các dependencies cần thiết
RUN apt-get update && apt-get install -y \
    nginx \
    && rm -rf /var/lib/apt/lists/*

# Sao chép mã nguồn Laravel vào thư mục /var/www/html trong container
COPY . /var/www/html

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Cài đặt composer và các dependencies của Laravel
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-scripts --no-autoloader

# Thiết lập quyền cho thư mục storage và bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Sao chép file cấu hình Nginx vào container
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Mở cổng 80 để truy cập vào Nginx
EXPOSE 80

# Bật máy chủ Nginx và PHP-FPM khi container được chạy
CMD nginx -g "daemon off;" && php-fpm

