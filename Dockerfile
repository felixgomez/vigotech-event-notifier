FROM php:cli
RUN apt-get update && apt-get install -y \
        git \
        zip \
        libicu-dev \
        && docker-php-ext-install intl
RUN curl --silent --show-error https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer && chmod u+x /usr/local/bin/composer
WORKDIR /app
CMD ["tail", "-f", "/dev/null"]
