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

# Auto-generate APP_KEY on first boot if empty (idempotent — skipped if already set).
# NOTE: /var/www/html/.env is a *single-file* bind mount from the host, so we can't
# use `sed -i` (which rename()s over the file and fails with "Resource busy" on
# bind mounts). Instead we rewrite the file contents in place with `cat >`, which
# truncates and writes to the same inode and is allowed.
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "base64:" ]; then
    echo "APP_KEY is empty, generating one..."
    GENERATED_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
    export APP_KEY="$GENERATED_KEY"

    if [ -f /var/www/html/.env ] && [ -w /var/www/html/.env ]; then
        tmp="$(mktemp)"
        if grep -q '^APP_KEY=' /var/www/html/.env; then
            awk -v key="APP_KEY=${GENERATED_KEY}" \
                'BEGIN{done=0} /^APP_KEY=/{print key; done=1; next} {print} END{if(!done) print key}' \
                /var/www/html/.env > "$tmp"
        else
            cat /var/www/html/.env > "$tmp"
            printf '\nAPP_KEY=%s\n' "${GENERATED_KEY}" >> "$tmp"
        fi
        # Overwrite the bind-mounted inode (cannot rename across the mount).
        cat "$tmp" > /var/www/html/.env
        rm -f "$tmp"
        echo "APP_KEY written to /var/www/html/.env"
    else
        echo "WARNING: /var/www/html/.env is missing or not writable."
        echo "         Runtime APP_KEY is set for this boot only — it WILL regenerate on restart."
        echo "         Add this line to the host .env to make it permanent:"
        echo "         APP_KEY=${GENERATED_KEY}"
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
