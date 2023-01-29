FROM php:8.2-fpm-alpine

WORKDIR /app

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

COPY . .
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY ./docker/ci/php/overrides.ini /usr/local/etc/php/conf.d/overrides.ini
COPY ./docker/ci/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN apk add --no-cache --update \
    icu-dev supervisor yarn && \
    chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions amqp intl pdo_mysql redis gmagick && \
    composer install && \
    yarn install

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
