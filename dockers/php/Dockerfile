FROM php:8.2-fpm


RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libmemcached-dev

RUN apt-get install -y  libonig-dev
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

COPY ./calculator.ini /usr/local/etc/php/conf.d/calculator.ini
# RUN pecl install memcached \
#     && docker-php-ext-enable memcached


RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install exif
RUN docker-php-ext-install pcntl

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


RUN curl -fsSL https://deb.nodesource.com/setup_18.x | bash -
RUN apt-get install -y nodejs


RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY --chown=www:www . /var/www

WORKDIR /var/www

USER www
RUN composer global require laravel/installer
RUN export PATH="$HOME/.composer/vendor/bin:$PATH"

EXPOSE 9000
CMD ["php-fpm"]
