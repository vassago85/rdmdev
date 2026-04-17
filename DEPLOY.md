# RDM Developments - Deployment Guide

## Repository
- **GitHub:** https://github.com/vassago85/rdmdev
- **Server:** 41.72.157.26
- **Server path:** `/opt/rdmdev`
- **URL:** https://rdmdev.co.za

---

## Architecture

Three containers, following the same pattern as NRAPA / MBP:

| Container          | Image             | Role                                      |
| ------------------ | ----------------- | ----------------------------------------- |
| `rdmdev-app`       | `rdmdev-app:latest` | Nginx + PHP-FPM 8.3 (Laravel 11, Filament) |
| `rdmdev-db`        | `mysql:8.0`       | Database (internal-only, no exposed port) |
| `rdmdev-scheduler` | `rdmdev-app:latest` | Runs `php artisan schedule:run` every minute |

Public traffic enters via **Nginx Proxy Manager** on the shared
`nginx-proxy-manager_default` network; `rdmdev.co.za` forwards to `rdmdev-app:80`.

Persistent volumes:
- `rdmdev-db` — MySQL data
- `rdmdev-storage` — `storage/app/*` (uploaded project/service images)
- `rdmdev-logs` — `storage/logs/*`

---

## First-time deployment

### Option A: One-shot installer (recommended)

From a fresh SSH session on the server:

```bash
curl -fsSL https://raw.githubusercontent.com/vassago85/rdmdev/main/docker/install.sh | bash
```

That single command will:
- Create `/opt/rdmdev`, clone the repo
- Generate `.env` with strong random DB passwords
- Build the Docker image, start the stack
- Wait for the app to become healthy
- Prompt you for admin user details

For non-interactive (CI) runs:
```bash
ADMIN_NAME="Paul Charsley" ADMIN_EMAIL="paul@charsley.co.za" ADMIN_PASSWORD="..." \
    bash <(curl -fsSL https://raw.githubusercontent.com/vassago85/rdmdev/main/docker/install.sh)
```

Flags: `--skip-admin`, `--skip-build`.

After it finishes, complete steps 5 and 6 below (NPM proxy + DNS).

---

### Option B: Manual step-by-step

### 1. On the server

```bash
ssh paul@41.72.157.26

sudo mkdir -p /opt/rdmdev
sudo chown paul:paul /opt/rdmdev
cd /opt/rdmdev

git clone https://github.com/vassago85/rdmdev.git .

cp docker/env.production.example .env
```

### 2. Edit `.env` and set at minimum:
- `DB_PASSWORD` (use `openssl rand -base64 32`)
- `DB_ROOT_PASSWORD` (different value, same command)
- `MAIL_*` (if sending enquiry emails via SMTP)

Leave `APP_KEY` blank — we'll generate it after the first build.

### 3. Build and start

```bash
cd /opt/rdmdev
docker compose -f docker-compose.prod.yml build
docker compose -f docker-compose.prod.yml up -d
```

The entrypoint will:
- Auto-generate `APP_KEY` if empty and persist it to `.env`
- Wait for the database
- Run migrations
- Seed the database on first boot only
- Warm config/route/view caches
- Create the `storage:link` symlink

So after `up -d` the public site is already live.

### 4. Create a Filament admin user (interactive — run once)

```bash
docker compose -f docker-compose.prod.yml exec app php artisan make:filament-user
```

You'll be prompted for Name / Email / Password. Log in at `https://rdmdev.co.za/admin`.

Non-interactive alternative:
```bash
docker compose -f docker-compose.prod.yml exec app php artisan make:filament-user \
  --name="Your Name" --email="you@example.com" --password="ChangeMeAfterwards"
```

### 5. Configure Nginx Proxy Manager
- Domain: `rdmdev.co.za` (and `www.rdmdev.co.za`)
- Forward hostname: `rdmdev-app`
- Forward port: `80`
- Enable Block Common Exploits
- SSL: request Let's Encrypt, enable "Force SSL" and HTTP/2

### 6. Point DNS
- `A` record `rdmdev.co.za` → `41.72.157.26`
- `A` record `www.rdmdev.co.za` → `41.72.157.26`

Site should be live at https://rdmdev.co.za and admin at https://rdmdev.co.za/admin.

---

## Subsequent deployments (pull & rebuild)

```bash
cd /opt/rdmdev && git pull origin main && docker compose -f docker-compose.prod.yml build --no-cache app && docker compose -f docker-compose.prod.yml up -d --force-recreate app scheduler
```

The `app` container's entrypoint automatically runs migrations, storage:link,
and cache warming on every start, so you usually don't need to run those manually.

---

## Common operations

### View logs
```bash
docker logs rdmdev-app --tail 100 -f
docker logs rdmdev-scheduler --tail 50
docker compose -f docker-compose.prod.yml exec app tail -50 storage/logs/laravel.log
```

### Run a migration manually
```bash
docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
```

### Clear / rebuild caches
```bash
docker compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker compose -f docker-compose.prod.yml exec app php artisan optimize
```

### Shell into the app container
```bash
docker compose -f docker-compose.prod.yml exec app sh
```

### Database shell
```bash
docker compose -f docker-compose.prod.yml exec db mysql -u rdmdev -p rdmdev
```

### Database backup
```bash
docker compose -f docker-compose.prod.yml exec db \
  mysqldump -u root -p"$DB_ROOT_PASSWORD" rdmdev > ~/backups/rdmdev-$(date +%F).sql
```

---

## Troubleshooting

### 502 Bad Gateway from NPM
- Check `docker ps` — is `rdmdev-app` running and healthy?
- Check `docker logs rdmdev-app` for nginx/php-fpm errors.
- Verify NPM is on the same `nginx-proxy-manager_default` network:
  ```bash
  docker network inspect nginx-proxy-manager_default | grep rdmdev
  ```

### 500 error on the site
```bash
docker compose -f docker-compose.prod.yml exec app tail -100 storage/logs/laravel.log
```

### Database connection error
```bash
docker restart rdmdev-db
sleep 10
docker restart rdmdev-app rdmdev-scheduler
```

### Uploaded images don't appear
The `storage/app/public` volume needs a public symlink. The entrypoint runs
`php artisan storage:link` on every start, but to force it:
```bash
docker compose -f docker-compose.prod.yml exec app php artisan storage:link
```
