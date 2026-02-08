FROM php:8.3-fpm-alpine

# System dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    bash \
    git \
    zip \
    unzip \
    libpng \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    tzdata \
    cronie

# PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    exif \
    pcntl \
    intl \
    gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy only package files first
COPY package.json package-lock.json ./

# Install Node.js and NPM, then install JS dependencies
RUN apk add --no-cache nodejs npm && npm install

# Copy backend
COPY . .
ENV APP_ENV=production
ENV APP_DEBUG=false

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Build front-end assets
RUN npm run build

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Nginx config
COPY nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

# Proper foreground startup
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
