FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    # For php zip ext
    zlib1g-dev libzip-dev \
    #for xml ext \
    libxml2 libxml2-dev \
    # Install required packages
    default-mysql-client \
    locales \
    # For php composer
    unzip \
    && apt-get clean \
    # install php ext
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) gettext \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) tokenizer \
    && docker-php-ext-install -j$(nproc) bcmath \
    && docker-php-ext-install -j$(nproc) zip

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
