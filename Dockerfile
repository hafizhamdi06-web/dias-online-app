# ---- vendor: resolve Composer deps for PHP 7.4 ----
FROM composer:2 AS vendor

# composer:2 is Alpine-based; mpdf (locked dep) requires gd at composer install
RUN apk add --no-cache \
		libpng-dev \
		libjpeg-turbo-dev \
		freetype-dev \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install gd

WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# ---- runtime: Apache + PHP 7.4, self-contained (no bind mount needed) ----
FROM php:7.4-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
		libzip-dev \
		zip \
		unzip \
		libpng-dev \
		libjpeg-dev \
		libfreetype6-dev \
		libonig-dev \
		libcurl4-openssl-dev \
	&& rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mysqli mbstring zip curl \
	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
	&& docker-php-ext-install gd

# .htaccess at repo root must be honored by the default DocumentRoot /var/www/html
RUN a2enmod rewrite \
	&& sed -ri 's/AllowOverride None/AllowOverride All/g' /etc/apache2/apache2.conf

# Custom php.ini (carried over 1:1 from the old image)
RUN echo "short_open_tag=On" > /usr/local/etc/php/conf.d/custom.ini \
	&& echo "output_buffering=On" >> /usr/local/etc/php/conf.d/custom.ini \
	&& echo "error_reporting=E_ALL & ~E_NOTICE & ~E_WARNING" >> /usr/local/etc/php/conf.d/custom.ini

WORKDIR /var/www/html
COPY --from=vendor /app/vendor ./vendor
COPY . .

RUN chown -R www-data:www-data application/cache application/logs

EXPOSE 80
