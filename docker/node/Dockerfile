FROM node:16-alpine

RUN apk update && apk upgrade && \
  apk add --no-cache git && \
  apk add --no-cache python3 && \
  apk add --no-cache make && \
  apk add --no-cache g++ && \
  apk add --no-cache bash

ARG home=/var/www
WORKDIR $home
RUN chown -R node:node /usr/local/lib
RUN chown -R node:node /var/www

RUN npm i -g npm

COPY package*.json $home/

CMD ["tail", "-f", "/dev/null"]
