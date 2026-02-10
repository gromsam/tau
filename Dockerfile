###################################
# Node build stage (Vite build)
###################################
FROM node:22-alpine AS node-builder
WORKDIR /var/www

COPY package.json package-lock.json ./
RUN npm ci --legacy-peer-deps

COPY . .
RUN npm run build


###################################
# PHP stage
###################################
FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    libpq-dev \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    linux-headers \
    $PHPIZE_DEPS

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    opcache \
    pcntl

RUN pecl install redis && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# копируем только build фронта
COPY --from=node-builder /var/www/public/build ./public/build

# копируем код
COPY . .

# entrypoint
COPY docker/php/entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000
CMD ["php-fpm"]
