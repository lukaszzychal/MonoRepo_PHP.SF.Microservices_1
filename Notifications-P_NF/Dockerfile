FROM php:8.1-cli
COPY . /var/www/html
WORKDIR /var/www/html

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions gd xdebug

COPY ./docker/php/xdebug.ini.dev /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini