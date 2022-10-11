FROM php:8.1-fpm-alpine
LABEL Name=P_AE-LukaszZychal Version=0.0.1
RUN apk update && apk add wget git
# RUN apk add php81-pecl-xdebug

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 80