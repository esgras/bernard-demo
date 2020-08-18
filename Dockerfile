FROM php:7.4-fpm

# set main params
ENV APP_HOME /var/www/html
ARG UID=1000
ARG GID=1000
ENV USERNAME=www-data

## Fix permissions
RUN mkdir -p /home/$USERNAME \
    && chown -R $USERNAME:$USERNAME /home/$USERNAME \
    && usermod -u $UID $USERNAME -d /home/$USERNAME \
    && groupmod -g $GID $USERNAME


# install all the dependencies and enable PHP modules
RUN apt-get update && apt-get upgrade -y && apt-get install -y \
      procps \
      nano \
      unzip \
      libicu-dev \
      zlib1g-dev \
      libxml2 \
      libxml2-dev \
      libreadline-dev \
      supervisor \
      cron \
      libzip-dev \
      librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
      pdo_mysql \
      sockets \
      intl \
      opcache \
      zip \
    && docker-php-ext-enable amqp \
    && rm -rf /tmp/* \
    && rm -rf /var/list/apt/* \
    && rm -rf /var/lib/apt/lists/* \
    && apt-get clean

COPY ./docker/dev/php.ini /usr/local/etc/php/php.ini

RUN pecl install xdebug

# Copy xdebug configuration for remote debugging
COPY ./docker/dev/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN chmod +x /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER 1

WORKDIR $APP_HOME

USER $USERNAME
