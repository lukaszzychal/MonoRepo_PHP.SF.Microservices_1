FROM php:8.1-fpm-alpine
LABEL Name=P_AE-LukaszZychal Version=0.0.1
RUN apk update && apk add wget git htop
RUN apk --update --no-cache add \
    postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql
RUN  apk add --update --no-cache  --virtual .build-dependencies $PHPIZE_DEPS \
 && pecl install apcu && docker-php-ext-enable apcu && pecl clear-cache 

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache bash 
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash 
RUN apk add symfony-cli
RUN apk add \ 
--no-cache $PHPIZE_DEPS \ 
 &&  apk add --update linux-headers \
 && pecl install xdebug-3.2.0alpha2 \
 && docker-php-ext-enable xdebug \
 && docker-php-ext-configure intl \
 && docker-php-ext-install \
        intl \
 && docker-php-ext-enable intl 
 
WORKDIR /var/www/html
# Rozwiązaneie tymczasowe
RUN chmod -R ugo+rwx .

EXPOSE 80