FROM php:7.4-apache

WORKDIR /var/www/html

# System deps + PHP extensions commonly required by Laravel apps
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
    bcmath \
    exif \
    gd \
    intl \
    mysqli \
    opcache \
    pdo_mysql \
    sockets \
    zip \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

# ffmpeg install
RUN apt-get update && apt-get install -y ffmpeg

# Composer (optional but handy for running installs inside the container)
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Apache vhost: point DocumentRoot to Laravel's /public
COPY docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

COPY docker/entrypoint.sh /usr/local/bin/streamly-entrypoint
RUN chmod +x /usr/local/bin/streamly-entrypoint

ENTRYPOINT ["streamly-entrypoint"]
CMD ["apache2-foreground"]
