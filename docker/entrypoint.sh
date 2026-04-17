#!/bin/sh
set -e

echo "Starting RDM Developments..."

mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/storage/app/public/services
mkdir -p /var/www/html/storage/app/public/projects
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/bootstrap/cache

# Auto-generate APP_KEY on first boot if empty (idempotent — skipped if already set)
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "APP_KEY is empty, generating one..."
    GENERATED_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    export APP_KEY="$GENERATED_KEY"
    if [ -f /var/www/html/.env ] && grep -q '^APP_KEY=' /var/www/html/.env; then
        sed -i "s|^APP_KEY=.*|APP_KEY=${GENERATED_KEY}|" /var/www/html/.env
        echo "APP_KEY written to /var/www/html/.env"
    else
        echo "WARNING: could not persist APP_KEY to .env (no .env file or missing APP_KEY line). Runtime env var is set, but will regenerate on next container restart. Add APP_KEY=${GENERATED_KEY} to the host .env to make it permanent."
    fi
fi

echo "Waiting for database..."
max_tries=30
count=0
until nc -z db 3306 2>/dev/null; do
    count=$((count + 1))
    if [ $count -ge $max_tries ]; then
        echo "Database not reachable after $max_tries attempts"
        exit 1
    fi
    echo "   Attempt $count/$max_tries - waiting for db:3306..."
    sleep 2
done
echo "Database port is open"

sleep 3

echo "Testing database credentials..."
max_tries=10
count=0
until php -r "new PDO('mysql:host=db;port=3306;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    count=$((count + 1))
    if [ $count -ge $max_tries ]; then
        echo "Database authentication failed after $max_tries attempts"
        echo "   Host: db, Database: ${DB_DATABASE}, User: ${DB_USERNAME}"
        break
    fi
    echo "   Auth attempt $count/$max_tries..."
    sleep 2
done
echo "Database is ready"

echo "Running migrations..."
php artisan migrate --force || echo "Migration had issues, continuing..."

# Seed on first boot only (idempotent guard: skip if services table already has rows)
if php artisan tinker --execute="echo \\App\\Models\\Service::count();" 2>/dev/null | grep -qE '^0$'; then
    echo "Seeding database (first boot)..."
    php artisan db:seed --force || echo "Seeder skipped"
fi

echo "Clearing caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear || true

echo "Caching for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

php artisan storage:link 2>/dev/null || true

chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

echo "RDM Developments is ready."

exec /usr/bin/supervisord -c /etc/supervisord.conf
