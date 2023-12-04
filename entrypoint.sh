#!/bin/bash
# if the .env is not here -- create a new one from the .env.example file
if [ ! -f ".env" ]; then
    echo "Creating .env file for env $APP_ENV"
    cp .env.example .env
else
    echo ".env file exists"
fi

# run the migrations --force to be able to do it in production mode -- else it is canceled by laravel
php artisan migrate --force
# run the seeders
php artisan db:seed --force

# clear the caches that caused some routes not to load correctly
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Serve the app on 0.0.0.0 because 127.0.0.1 is on the bridge network
# use the dynamic port provided in the Dockerfile
php artisan serve --host=0.0.0.0 --port=$PORT --env=.env
exec docker-php-entrypoint "$@"