FROM node:lts-alpine3.17

WORKDIR /app

COPY . /app/
COPY ./docker/ci/encore/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN apk add --no-cache --update supervisor && \
    composer install && \
    yarn install

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
