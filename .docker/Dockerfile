FROM php:8.0.12-fpm

## INSTALL DEPENCENCIES ##
RUN apt-get update -y && apt upgrade -y && apt-get install -y \
    gcc \
    git \
    openssl \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libzip-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libpcre3-dev \
    build-essential \
    libc-ares-dev

RUN docker-php-ext-install gd xml zip mbstring pcntl bcmath pgsql
RUN docker-php-ext-configure pcntl --enable-pcntl

RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
