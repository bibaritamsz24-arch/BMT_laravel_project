FROM node:22-bookworm AS assets

WORKDIR /app

COPY package.json ./
RUN npm install

COPY resources ./resources
COPY vite.config.js ./
RUN npm run build

FROM php:8.3-apache

WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        libzip-dev \
        unzip \
        zip \
    && docker-php-ext-install pdo_mysql zip \
    && a2enmod rewrite headers \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start

RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && chmod +x /usr/local/bin/start \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 10000

CMD ["/usr/local/bin/start"]
