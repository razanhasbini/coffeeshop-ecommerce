#!/usr/bin/env sh
set -eu

export APACHE_HTTP_PORT="${PORT:-10000}"

sed -ri "s/Listen 80/Listen ${APACHE_HTTP_PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${APACHE_HTTP_PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan storage:link || true
php docker/prepare-database.php
php artisan migrate --force
php artisan db:seed --force
php artisan optimize

exec apache2-foreground
