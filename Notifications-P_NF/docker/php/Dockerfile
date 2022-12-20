FROM php:8.1-fpm-alpine 
LABEL Name=P_AE-LukaszZychal Version=0.0.1
WORKDIR /var/www/html
COPY ./ /var/www/html
RUN apk add wget git htop postgresql-dev bash 
RUN docker-php-ext-install pdo pdo_pgsql  
RUN apk add --update --no-cache  --virtual .build-dependencies $PHPIZE_DEPS 

RUN pecl install apcu && docker-php-ext-enable apcu && pecl clear-cache 
 RUN apk add \ 
--no-cache $PHPIZE_DEPS \ 
 &&  apk add --update linux-headers \
 && pecl install xdebug-3.2.0alpha2 \
 && docker-php-ext-enable xdebug \
 && docker-php-ext-configure intl \
 && docker-php-ext-install \
        intl \
 && docker-php-ext-enable intl 

RUN curl -sS https://get.symfony.com/cli/installer | bash \
 $ mv /root/.symfony5/bin/symfony /usr/local/bin/symfony
COPY --from=composer/composer /usr/bin/composer /usr/bin/composer

 

# RUN addgroup -S nf && adduser -S nf -G nf
# USER nf:nf

# CMD [ "composer", "install" ] 

EXPOSE 80




