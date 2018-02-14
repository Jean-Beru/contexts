FROM jwilder/dockerize:0.6.0
FROM composer:1.6
FROM php:5.6-alpine

# Workdir
WORKDIR /srv

# Install dependencies
RUN apk add --no-cache curl && \
    docker-php-ext-install bcmath

# Dockerize & Composer
COPY --from=0 /usr/local/bin /usr/local/bin
COPY --from=1 /usr/bin/composer /usr/bin/composer
