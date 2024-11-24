FROM php:7.4-fpm

# Установка расширения mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Установка дополнительных полезных расширений (опционально)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    && docker-php-ext-install zip

# Копируем проект в контейнер
WORKDIR /var/www/html
COPY . /var/www/html

# Устанавливаем права доступа
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Убедимся, что PHP-FPM использует правильный сокет
CMD ["php-fpm"]
