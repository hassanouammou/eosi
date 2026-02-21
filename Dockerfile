FROM php:8.2-apache

RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

COPY . /var/www/html/

COPY docker/apache/vhost.template.conf /etc/apache2/sites-available/000-default.template.conf
COPY docker/apache/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

RUN mkdir -p /var/www/html/upload/photo/administrator \
    /var/www/html/upload/photo/employee \
    /var/www/html/upload/electronic-mail/incoming-mail \
    /var/www/html/upload/electronic-mail/outgoing-mail \
    && chown -R www-data:www-data /var/www/html/upload

ENV APACHE_DOCUMENT_ROOT=/var/www/html
EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]
