#!/bin/sh

cd /var/www &&
composer install
#composer update
php artisan migrate
php artisan storage:link
php-fpm
