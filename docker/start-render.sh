#!/usr/bin/env sh
set -eu

export APACHE_HTTP_PORT="${PORT:-10000}"
export SESSION_DRIVER=file
export CACHE_STORE=file
export QUEUE_CONNECTION=sync
export LOG_CHANNEL=stderr

# Never boot production with Laravel manifests generated on a developer machine.
# Those manifests may reference packages omitted by Composer's --no-dev install.
find bootstrap/cache -maxdepth 1 -type f -name '*.php' -delete

sed -ri "s/Listen 80/Listen ${APACHE_HTTP_PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${APACHE_HTTP_PORT}>/" /etc/apache2/sites-available/000-default.conf

php artisan storage:link || true
php docker/prepare-database.php
php artisan migrate --force
php artisan db:seed --force
php artisan optimize

# Startup commands run as root and can recreate Laravel's writable files.
# Restore ownership before Apache handles the first real request.
chown -R www-data:www-data storage bootstrap/cache

exec apache2-foreground
