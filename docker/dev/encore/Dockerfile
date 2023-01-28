FROM node:lts-alpine3.17

WORKDIR /app

RUN apk add --no-cache --update supervisor

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
