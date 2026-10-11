FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y libsqlite3-dev sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

COPY app/ /var/www/html/
COPY app/docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT ["/entrypoint.sh"]