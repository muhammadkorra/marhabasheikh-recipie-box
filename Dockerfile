FROM php:8.2 as php

RUN apt-get update -y
RUN apt-get install -y unzip zip libxml2-dev libonig-dev libpng-dev
# install necessary php extensions required by laravel
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd xml
# install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# switch workdir 
WORKDIR /var/www
# copy composer.json and compose.lock
COPY composer.json composer.lock ./
# copy application data -- usually should be after install by composer requires presence of artisan
COPY . .
# install dependencies
RUN composer install --no-progress --no-interaction --optimize-autoloader
# update cache ownership
RUN chown -R www-data:www-data storage bootstrap/cache
# copy entrypoint to the container
COPY entrypoint.sh /usr/local/bin
# make it executable
RUN chmod +x /usr/local/bin/entrypoint.sh
# make a dynamic port for the app
ENV PORT=8080
# expose the port on the container
EXPOSE 8080

ENTRYPOINT ["entrypoint.sh"]