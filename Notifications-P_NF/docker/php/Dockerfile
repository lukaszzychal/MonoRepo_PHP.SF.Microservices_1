FROM php:8.1-fpm-alpine 
LABEL Name=P_AE-LukaszZychal Version=0.0.1
WORKDIR /var/www/html
RUN apk add --virtual wget git htop postgresql-dev bash 
RUN docker-php-ext-install pdo pdo_pgsql  
RUN apk add --no-cache --virtual .build-dependencies $PHPIZE_DEPS 

RUN pecl install apcu && docker-php-ext-enable apcu && pecl clear-cache 
 RUN apk add --virtual \ 
--no-cache $PHPIZE_DEPS \ 
 &&  apk add linux-headers \
 && pecl install xdebug-3.2.0alpha2 \
 && docker-php-ext-enable xdebug \
 && docker-php-ext-configure intl \
 && docker-php-ext-install \
        intl \
 && docker-php-ext-enable intl 

RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash 
RUN apk add symfony-cli

COPY --from=composer/composer /usr/bin/composer /usr/bin/composer
COPY ./ /var/www/html
EXPOSE 80




