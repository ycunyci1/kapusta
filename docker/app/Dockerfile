FROM php:8.2-fpm

# ставим необходимые для нормальной работы модули
RUN apt-get update && apt-get install -y \
    curl \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
	libpng-dev \
	libonig-dev \
	libzip-dev \
	libmcrypt-dev \
    libmemcached-tools \
    libmemcached-dev \
        && pecl install mcrypt-1.0.6 && docker-php-ext-enable mcrypt \
        #&& pecl install redis && docker-php-ext-enable redis \
        #&& pecl install memcached && docker-php-ext-enable memcached \
        && docker-php-ext-install -j$(nproc) iconv mbstring mysqli pdo_mysql zip sockets\
	    && docker-php-ext-configure gd --with-freetype --with-jpeg \
        && docker-php-ext-install -j$(nproc) gd


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY php.ini /usr/local/etc/php/conf.d/custom.ini
COPY zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

WORKDIR /var/www/html

CMD ["php-fpm"]


