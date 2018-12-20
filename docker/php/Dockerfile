FROM php:fpm

ARG APP_PATH
ARG APP_USER

ENV HOME_PATH /home/${APP_USER}
ENV BACKEND_PATH ${APP_PATH}/backend

WORKDIR ${BACKEND_PATH}

# Setup mysql
RUN docker-php-ext-install pdo pdo_mysql opcache > /dev/null

# Setup composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Setup ZIP
RUN \
    apt-get update > /dev/null && \
    apt-get install -y git zip unzip zlib1g-dev && \
    apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* && \
    docker-php-ext-install zip


# Setup GD
RUN apt-get update && \
    apt-get install -y libfreetype6-dev libjpeg62-turbo-dev && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/  &&  \
    docker-php-ext-install gd

# setup intl extension
RUN apt-get update \
  && apt-get install -y zlib1g-dev libicu-dev g++ \
  && docker-php-ext-configure intl \
  && docker-php-ext-install intl

# Setup sSMTP for mail
RUN apt-get update && \
	apt-get install -y -q --no-install-recommends ssmtp && \
	apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Setup user
RUN \
    mkdir ${HOME_PATH}  /opt/vendor && \
    useradd ${APP_USER} -g users -s /bin/bash && \
    chown -R ${APP_USER}:www-data ${APP_PATH} ${HOME_PATH}  /opt/vendor

USER ${APP_USER}