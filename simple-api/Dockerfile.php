FROM php:8.2-fpm

# Instalar as extensões PDO e MySQL
RUN docker-php-ext-install pdo pdo_mysql opcache && \
    docker-php-ext-enable opcache

RUN apt-get update && apt-get install -y procps && apt-get clean

# Copia o código da aplicação
COPY . /var/www/html

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY ./simple-api/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www/html

EXPOSE 9000
