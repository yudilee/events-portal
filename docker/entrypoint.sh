#!/bin/sh
set -e

# Ensure storage and bootstrap/cache permissions
mkdir -p /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/framework/cache \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache

chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# Execute Supervisord
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
