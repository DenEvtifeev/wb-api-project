FROM php:8.1-apache

WORKDIR /var/www/html

RUN docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apt-get update && apt-get install -y cron

# Копируем cron файл (укажите путь, если планируете добавить задачи cron в Docker)
COPY ./app_cron /etc/cron.d/app_cron

# Делаем cron файл исполняемым
RUN chmod 0644 /etc/cron.d/app_cron

# Добавляем cron в список запускаемых служб
RUN crontab /etc/cron.d/app_cron

# Убедитесь, что Apache и Cron запускаются вместе
CMD ["sh", "-c", "composer install && service cron start && apache2-foreground"]

