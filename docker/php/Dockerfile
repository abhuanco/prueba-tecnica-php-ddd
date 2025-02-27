FROM php:8.4-fpm

RUN apt-get update -y \
    && apt-get install -y --no-install-recommends \
       build-essential \
       vim wget \
       dialog \
       git \
       libxml2-dev \
       libicu-dev \
       zlib1g-dev \
       zip unzip \
       g++ \
       libpng-dev \
       libjpeg-dev \
       libfreetype6-dev \
       libcurl4 \
       libcurl4-openssl-dev \
       openssl \
       ssl-cert \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install gd dom fileinfo filter pdo pdo_mysql ctype intl \
    && docker-php-ext-enable ctype intl

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN curl -sSL https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions -o install-php-extensions \
    && chmod +x install-php-extensions \
    && ./install-php-extensions mbstring xdebug bcmath soap gettext opcache simplexml mysqli iconv hash json

COPY ./etc/php.ini /usr/local/etc/php/conf.d/php.ini

RUN apt-get autoremove -y && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && usermod -u 1000 www-data \
    && chown -R www-data:www-data /var/www/html

EXPOSE 9000