FROM wordpress:6.9.4-php8.2-apache

COPY docker/apache/gym-community.conf /etc/apache2/conf-available/gym-community.conf

RUN a2enmod rewrite \
    && a2enconf gym-community \
    && printf "ServerName localhost\n" > /etc/apache2/conf-available/servername.conf \
    && a2enconf servername

WORKDIR /var/www/html
