FROM node:lts-alpine3.17

WORKDIR /app

COPY . /app/

RUN apk add --no-cache --update supervisor && \
    yarn install

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
