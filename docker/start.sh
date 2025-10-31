#!/bin/sh

# Create SQLite database if it doesn't exist
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
    chmod 666 /var/www/html/database/database.sqlite
    chown www-data:www-data /var/www/html/database/database.sqlite
fi

# Run migrations and seed
php artisan migrate --force --seed

# Serve the API
php artisan serve --port 8005 --host 0.0.0.0

# Start supervisor
exec /usr/bin/supervisord -c /etc/supervisord.conf
