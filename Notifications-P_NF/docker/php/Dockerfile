FROM php:8.1-fpm-alpine
LABEL Name=P_AE-LukaszZychal Version=0.0.1
RUN apk update && apk add wget git
# RUN apk add php81-pecl-xdebug
RUN apk --no-cache add \
    postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
RUN chmod -R ugo+rwx .

EXPOSE 80