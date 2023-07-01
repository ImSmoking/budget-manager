FROM php:8.2-fpm

ARG DOCKER_UID
ARG DOCKER_GID

ENV DOCKER_UID=${DOCKER_UID}
ENV DOCKER_GID=${DOCKER_GID}

WORKDIR /app/

RUN addgroup --gid ${DOCKER_GID} --system appuser
RUN adduser --ingroup appuser --system --disabled-password --shell /bin/sh --uid ${DOCKER_UID} appuser

RUN sed -i "s/user = www-data/user = appuser/g" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "s/group = www-data/group = appuser/g" /usr/local/etc/php-fpm.d/www.conf
RUN echo "php_admin_flag[log_errors] = on" >> /usr/local/etc/php-fpm.d/www.conf

# Install OS dependencies
RUN apt-get update;
RUN apt-get install -y zlib1g-dev libicu-dev libzip-dev unzip figlet


# Install general PHP modules
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl
RUN docker-php-ext-install zip
RUN docker-php-ext-install pdo_mysql

RUN pecl install xdebug-3.2.1 redis-5.3.7 && docker-php-ext-enable xdebug redis


RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Install composer
RUN curl https://getcomposer.org/installer > composer-setup.php && \
    php composer-setup.php && \
    mv composer.phar /usr/local/bin/composer && \
    rm composer-setup.php

# Install Symfony CLI.
RUN echo 'deb [trusted=yes] https://repo.symfony.com/apt/ /' | tee /etc/apt/sources.list.d/symfony-cli.list \
    && apt update && apt install -y symfony-cli

RUN chown -R appuser:appuser /app
USER appuser
COPY setup.sh /home/appuser/