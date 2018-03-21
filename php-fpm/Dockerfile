FROM php:7.1-fpm

WORKDIR /var/www/

RUN apt-get update && \
    apt-get install -y \
        libmcrypt-dev \
        cron \
        unzip \
        pigz \
        git \
        mysql-client \
        libicu-dev \
        supervisor \
        curl \
        openssl \
        libjpeg62-turbo-dev \
        libpng12-dev \
        libssl-dev \
        pkg-config \
        vim \
        --no-install-recommends \
    && pecl install mongodb \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        mcrypt \
        pdo_mysql \
        intl \
        opcache \
        pcntl \
        gd \
    && docker-php-ext-enable mongodb \
    && docker-php-ext-configure gd \
        --with-gd \
        --with-jpeg-dir=/usr/include/ \
        --with-png-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer --ansi --version --no-interaction \
    && composer global require hirak/prestissimo

RUN apt-get clean \
    && rm -rf \
            /var/lib/apt/lists/* \
            /tmp/* \
            /var/tmp/*

COPY ./config/php.ini /usr/local/etc/php/php.ini
COPY ./config/cron-file /etc/cron.d/cron-file
COPY ./config/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

RUN chmod 0644 /etc/cron.d/cron-file

#COPY ./www /var/www
COPY ./init.sh /usr/local/bin/init.sh
#COPY ./auth.json /var/www/auth.json

RUN chmod +x /usr/local/bin/init.sh
#RUN composer install --no-dev --no-progress --prefer-dist --optimize-autoloader --no-interaction --no-ansi
#RUN rm /var/www/auth.json
#RUN chown -R www-data: /var/www

CMD ["/usr/local/bin/init.sh"]
