FROM php:8.3.14-fpm
RUN apt-get update && apt-get install -y \
    htop \
    nano \
    apt-utils \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    zlib1g-dev \
    libzip-dev \
    libldap2-dev \
    zip unzip \
    git && \
    docker-php-ext-configure ldap && \
    docker-php-ext-install ldap && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-install bcmath && \
    docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install gd && \
    docker-php-ext-install zip && \
    docker-php-ext-install sockets && \
    docker-php-ext-configure calendar && docker-php-ext-install calendar && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# xdebug
ARG XDEBUG
RUN if [ "$XDEBUG" = true ]; then \
    apt-get update && apt-get install -y \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && echo "xdebug.mode=coverage" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=trigger" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini; \
fi

# Needed for web-token/jwt-core & web-token/jwt-signature
RUN apt-get update && apt-get install -y libgmp-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*docker

RUN docker-php-ext-install gmp;
RUN docker-php-ext-install opcache
COPY ./_docker/local/app/opcache.ini /usr/local/etc/php/conf.d/opcache.ini
COPY ./_docker/local/app/php.ini /usr/local/etc/php/conf.d/php.ini

# Install composer
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin
# Alias
RUN echo "alias a='artisan'" >> /root/.bashrc

# Simplexml
RUN apt-get update && apt-get install -y libxml2-dev && \
    apt-get clean && \
    docker-php-ext-install simplexml && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/

WORKDIR /var/www

CMD ["php-fpm"]
