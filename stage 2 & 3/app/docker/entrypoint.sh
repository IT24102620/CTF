#!/bin/bash
set -e 

echo "Initializing database..."
rm -f /var/www/html/db/Dinithdatabase.sqlite
sqlite3 /var/www/html/db/Dinithdatabase.sqlite < /var/www/html/db/seed.sql
chown www-data:www-data /var/www/html/db/Dinithdatabase.sqlite

exec apache2-foreground