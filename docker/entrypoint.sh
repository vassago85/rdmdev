#!/bin/sh
set -e

# Safety: if we crash before supervisord, wait 5s before exiting so Docker's
# `restart: unless-stopped` loop doesn't spin hundreds of times per minute
# (which corrupts containerd state and produces "marked for removal" zombies).
trap 'rc=$?; echo "Entrypoint exited with code $rc — sleeping 5s before Docker restarts us"; sleep 5; exit $rc' EXIT INT TERM

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

# APP_KEY must be set by the host .env (via env_file in compose).
# We no longer try to persist it inside the container because bind-mounting
# a single file causes permission / inode rename issues on some hosts.
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "ERROR: APP_KEY is empty." >&2
    echo "       Generate one on the host and add it to /opt/rdmdev/.env:" >&2
    echo "         cd /opt/rdmdev" >&2
    echo "         KEY=\"base64:\$(openssl rand -base64 32)\"" >&2
    echo "         sed -i \"s|^APP_KEY=.*|APP_KEY=\${KEY}|\" .env" >&2
    echo "       Then restart: docker compose -f docker-compose.prod.yml up -d" >&2
    exit 1
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
auth_ok=0
until php -r "new PDO('mysql:host=db;port=3306;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; do
    count=$((count + 1))
    if [ $count -ge $max_tries ]; then
        break
    fi
    echo "   Auth attempt $count/$max_tries..."
    sleep 2
done

if php -r "new PDO('mysql:host=db;port=3306;dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}');" 2>/dev/null; then
    auth_ok=1
    echo "Database is ready"
else
    echo "ERROR: Database authentication failed after $max_tries attempts."
    echo "       Host: db, Database: ${DB_DATABASE}, User: ${DB_USERNAME}"
    echo "       Most likely cause: the DB volume was initialised with a different"
    echo "       password than what's currently in .env. Fix with one of:"
    echo "         1) Reset volume:  docker compose -f docker-compose.prod.yml down -v && up -d"
    echo "         2) Update user:   ALTER USER '${DB_USERNAME}'@'%' IDENTIFIED BY '<pass>';"
    exit 1
fi

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
