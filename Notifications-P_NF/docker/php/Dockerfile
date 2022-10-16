FROM php:8.1-fpm-alpine
LABEL Name=P_AE-LukaszZychal Version=0.0.1
RUN apk update && apk add wget git
# RUN apk add php81-pecl-xdebug
RUN apk --update --no-cache add \
    postgresql-dev
RUN  apk add --update --no-cache  --virtual .build-dependencies $PHPIZE_DEPS \
 && pecl install apcu && docker-php-ext-enable apcu && pecl clear-cache 

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
# Rozwiązaneie tymczasowe
RUN chmod -R ugo+rwx .

EXPOSE 80