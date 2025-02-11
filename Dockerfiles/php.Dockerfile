FROM php:8.1-apache

WORKDIR /var/www/html

# Обновляем пакеты и устанавливаем необходимые зависимости:
# libzip-dev и unzip – для работы с архивами,
# git – для скачивания исходников,
# cron – для задач по расписанию.
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    cron

# Устанавливаем PHP-расширения: pdo, pdo_mysql и zip
RUN docker-php-ext-install pdo pdo_mysql zip

# Копируем Composer из официального образа и устанавливаем его
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Копируем файл cron и устанавливаем необходимые права
COPY ./app_cron /etc/cron.d/app_cron
RUN chmod 0644 /etc/cron.d/app_cron
RUN crontab /etc/cron.d/app_cron

# При запуске контейнера автоматически устанавливаем зависимости, запускаем cron и Apache
CMD ["sh", "-c", "composer install --no-interaction --prefer-dist && service cron start && apache2-foreground"]
