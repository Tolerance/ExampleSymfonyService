FROM php:5.6-apache

# PHP extension
RUN requirements="zlib1g-dev libicu-dev git wget" \
    && apt-get update && apt-get install -y $requirements && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install intl \
    && docker-php-ext-install zip \
    && docker-php-ext-install opcache \
    && docker-php-ext-install bcmath \
    && pecl install apcu-4.0.8 && echo extension=apcu.so > /usr/local/etc/php/conf.d/apcu.ini \
    && apt-get purge --auto-remove -y

# Apache & PHP configuration
RUN a2enmod rewrite
ADD docker/apache/vhost.conf /etc/apache2/sites-enabled/000-default.conf
ADD docker/php/php.ini /usr/local/etc/php/php.ini

# Install composer
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/bin/composer

# Add the application
COPY . /app
WORKDIR /app

# Fixes permissions
RUN rm -rf var/cache/* && rm -rf var/logs/*

# Run dependencies
RUN composer install

# Fixes permissions
RUN rm -rf var/cache/* && rm -rf var/logs/* \
    && chown www-data . var/cache var/logs

CMD ["/app/docker/apache/run.sh"]
