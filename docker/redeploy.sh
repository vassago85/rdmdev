#!/usr/bin/env bash
#
# RDM Developments — full clean-slate redeploy.
#
# Destroys the existing rdmdev Docker stack (containers, DB volume, storage
# volumes), pulls the latest code, regenerates .env with fresh passwords,
# rebuilds the image from scratch, starts the stack, and waits for health.
#
# USE THIS WHEN a previous deploy ended up with stale DB creds, a wedged
# volume, or mystery PDO auth failures — it guarantees every moving part
# starts from a known-clean state.
#
# Usage:
#   bash /opt/rdmdev/docker/redeploy.sh
#
# Flags:
#   --keep-env       keep existing .env (only wipe containers/volumes/images)
#   --keep-storage   don't wipe the rdmdev-storage volume (preserve uploads)
#   --no-admin       don't create a Filament admin user at the end
#   --help / -h      show this help
#
# Env vars for non-interactive admin creation:
#   ADMIN_NAME, ADMIN_EMAIL, ADMIN_PASSWORD
set -euo pipefail

INSTALL_DIR="${INSTALL_DIR:-/opt/rdmdev}"
BRANCH="${BRANCH:-main}"
COMPOSE_FILE="docker-compose.prod.yml"

KEEP_ENV=0
KEEP_STORAGE=0
NO_ADMIN=0
for arg in "$@"; do
    case "$arg" in
        --keep-env)     KEEP_ENV=1 ;;
        --keep-storage) KEEP_STORAGE=1 ;;
        --no-admin)     NO_ADMIN=1 ;;
        --help|-h)      sed -n '2,25p' "$0"; exit 0 ;;
        *) echo "Unknown argument: $arg" >&2; exit 1 ;;
    esac
done

GREEN='\033[0;32m'; YELLOW='\033[1;33m'; RED='\033[0;31m'; BLUE='\033[0;34m'; NC='\033[0m'
step()    { echo -e "\n${BLUE}==>${NC} ${1}"; }
success() { echo -e "${GREEN}✓${NC} ${1}"; }
warn()    { echo -e "${YELLOW}⚠${NC} ${1}"; }
err()     { echo -e "${RED}✗${NC} ${1}" >&2; }

#---------- Pre-flight ----------
step "Pre-flight"

if [ "$EUID" -eq 0 ]; then
    err "Don't run as root. Run as your deploy user."
    exit 1
fi
[ -d "$INSTALL_DIR" ] || { err "$INSTALL_DIR does not exist. Run docker/install.sh first."; exit 1; }
cd "$INSTALL_DIR"
[ -f "$COMPOSE_FILE" ] || { err "$COMPOSE_FILE not found in $INSTALL_DIR"; exit 1; }
command -v docker >/dev/null || { err "docker missing"; exit 1; }
docker compose version >/dev/null 2>&1 || { err "docker compose v2 required"; exit 1; }

warn "This will:"
warn "  - Stop and remove all rdmdev containers"
warn "  - Delete the rdmdev database volume (ALL DATA)"
[ "$KEEP_STORAGE" -eq 0 ] && warn "  - Delete the rdmdev storage volume (uploads)"
[ "$KEEP_ENV"     -eq 0 ] && warn "  - Regenerate .env with fresh random passwords"
warn "  - Rebuild the app image from scratch"
echo ""
read -r -p "Continue? [y/N] " confirm
[ "$confirm" = "y" ] || [ "$confirm" = "Y" ] || { err "Aborted."; exit 1; }

#---------- Pull latest ----------
step "Pulling latest code on branch ${BRANCH}"
git fetch origin "$BRANCH"
git checkout "$BRANCH"
git pull --ff-only origin "$BRANCH"
success "Repo updated ($(git rev-parse --short HEAD))"

#---------- Destroy old stack ----------
step "Destroying existing stack"

# Stop + remove via compose (handles its own orphans)
docker compose -f "$COMPOSE_FILE" down --remove-orphans --volumes 2>/dev/null || true

# Belt-and-braces: nuke any leftover containers by name
for c in rdmdev-app rdmdev-db rdmdev-scheduler; do
    if docker ps -a --format '{{.Names}}' | grep -qx "$c"; then
        docker rm -f "$c" >/dev/null 2>&1 || true
        success "Removed leftover container: $c"
    fi
done

# Nuke the DB volume explicitly (compose --volumes above already did this,
# but be sure in case a stale reference exists)
for v in rdmdev_rdmdev-db rdmdev_rdmdev-logs; do
    if docker volume ls --format '{{.Name}}' | grep -qx "$v"; then
        docker volume rm -f "$v" >/dev/null 2>&1 || true
        success "Removed volume: $v"
    fi
done

if [ "$KEEP_STORAGE" -eq 0 ]; then
    if docker volume ls --format '{{.Name}}' | grep -qx "rdmdev_rdmdev-storage"; then
        docker volume rm -f rdmdev_rdmdev-storage >/dev/null 2>&1 || true
        success "Removed volume: rdmdev_rdmdev-storage"
    fi
else
    warn "Keeping storage volume (--keep-storage)"
fi

# Remove old image so we're forced to rebuild cleanly
if docker image ls --format '{{.Repository}}:{{.Tag}}' | grep -qx "rdmdev-app:latest"; then
    docker image rm -f rdmdev-app:latest >/dev/null 2>&1 || true
    success "Removed old image rdmdev-app:latest"
fi

#---------- .env ----------
if [ "$KEEP_ENV" -eq 1 ]; then
    step "Keeping existing .env (--keep-env)"
    [ -f .env ] || { err ".env not found but --keep-env passed"; exit 1; }
    # Validate required keys are set
    for key in DB_PASSWORD DB_ROOT_PASSWORD APP_KEY; do
        val=$(grep -E "^${key}=" .env | head -1 | cut -d= -f2- || true)
        if [ -z "$val" ] || [ "$val" = "CHANGE_ME" ] || [ "$val" = "CHANGE_ME_TOO" ]; then
            err "$key is empty or placeholder in .env"
            exit 1
        fi
    done
    success ".env validated"
else
    step "Regenerating .env with fresh credentials"
    # Backup existing .env (timestamped) before overwriting
    if [ -f .env ]; then
        cp .env ".env.bak.$(date +%Y%m%d-%H%M%S)"
        success "Backed up old .env"
    fi
    cp docker/env.production.example .env

    DB_PASS=$(openssl rand -base64 32 | tr -d '=+/' | cut -c1-32)
    DB_ROOT_PASS=$(openssl rand -base64 32 | tr -d '=+/' | cut -c1-32)

    sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|" .env
    sed -i "s|^DB_ROOT_PASSWORD=.*|DB_ROOT_PASSWORD=${DB_ROOT_PASS}|" .env

    # Generate and bake APP_KEY directly (entrypoint doesn't write .env anymore)
    APP_KEY_VAL="base64:$(openssl rand -base64 32)"
    sed -i "s|^APP_KEY=.*|APP_KEY=${APP_KEY_VAL}|" .env

    chmod 664 .env
    success "Fresh .env written with random DB passwords and APP_KEY"
fi

#---------- Build ----------
step "Building image from scratch (--no-cache)"
docker compose -f "$COMPOSE_FILE" build --no-cache app
success "Image built"

#---------- Up ----------
step "Starting stack"
docker compose -f "$COMPOSE_FILE" up -d
success "Containers started"

#---------- Wait for DB ----------
step "Waiting for rdmdev-db to be healthy"
max_wait=120; elapsed=0
while [ $elapsed -lt $max_wait ]; do
    status=$(docker inspect --format='{{.State.Health.Status}}' rdmdev-db 2>/dev/null || echo "missing")
    [ "$status" = "healthy" ] && { success "DB healthy"; break; }
    sleep 3; elapsed=$((elapsed+3)); printf "."
done
echo ""
[ "$status" = "healthy" ] || { err "DB never became healthy. Logs:"; docker logs rdmdev-db --tail 50; exit 1; }

#---------- Verify PDO connection ----------
step "Verifying PDO handshake from app → db"
set +e
docker compose -f "$COMPOSE_FILE" exec -T app php -r '
$h=getenv("DB_HOST"); $u=getenv("DB_USERNAME"); $p=getenv("DB_PASSWORD"); $d=getenv("DB_DATABASE");
try {
    $pdo = new PDO("mysql:host=$h;dbname=$d;charset=utf8mb4", $u, $p, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    echo "PDO OK\n";
} catch (Throwable $e) { echo "PDO FAIL: ".$e->getMessage()."\n"; exit(1); }
'
pdo_rc=$?
set -e
if [ $pdo_rc -ne 0 ]; then
    err "PDO handshake FAILED. This is the bug we were chasing."
    err "Dumping diagnostics:"
    echo ""
    echo "--- .env DB vars ---"
    grep -E '^DB_' .env | sed 's/PASSWORD=.*/PASSWORD=***/'
    echo ""
    echo "--- app container env DB vars ---"
    docker exec rdmdev-app env | grep -E '^DB_' | sed 's/PASSWORD=.*/PASSWORD=***/'
    echo ""
    echo "--- mysql user list ---"
    docker exec rdmdev-db mysql -uroot -p"$(grep ^DB_ROOT_PASSWORD .env | cut -d= -f2)" -e "SELECT user,host,plugin FROM mysql.user;"
    exit 1
fi
success "PDO handshake confirmed"

#---------- Wait for app health ----------
step "Waiting for rdmdev-app to become healthy"
max_wait=180; elapsed=0
while [ $elapsed -lt $max_wait ]; do
    status=$(docker inspect --format='{{.State.Health.Status}}' rdmdev-app 2>/dev/null || echo "missing")
    case "$status" in
        healthy)   success "App healthy"; break ;;
        unhealthy) err "App unhealthy. Logs:"; docker logs rdmdev-app --tail 50; exit 1 ;;
    esac
    sleep 3; elapsed=$((elapsed+3)); printf "."
done
echo ""

#---------- Admin user ----------
if [ "$NO_ADMIN" -eq 0 ]; then
    step "Creating Filament admin user"
    user_count=$(docker compose -f "$COMPOSE_FILE" exec -T app php artisan tinker --execute="echo \\App\\Models\\User::count();" 2>/dev/null | tail -1 | tr -d '\r\n ' || echo "0")
    if [ "$user_count" != "0" ] && [ -n "$user_count" ]; then
        success "User already exists (count=$user_count), skipping"
    elif [ -n "${ADMIN_NAME:-}" ] && [ -n "${ADMIN_EMAIL:-}" ] && [ -n "${ADMIN_PASSWORD:-}" ]; then
        docker compose -f "$COMPOSE_FILE" exec -T app php artisan make:filament-user \
            --name="$ADMIN_NAME" --email="$ADMIN_EMAIL" --password="$ADMIN_PASSWORD"
        success "Admin created: $ADMIN_EMAIL"
    else
        docker compose -f "$COMPOSE_FILE" exec app php artisan make:filament-user
    fi
else
    warn "Skipping admin creation (--no-admin). Create one later with:"
    warn "  docker compose -f $COMPOSE_FILE exec app php artisan make:filament-user"
fi

#---------- Summary ----------
step "Redeploy complete"
docker compose -f "$COMPOSE_FILE" ps

cat <<EOF

${GREEN}────────────────────────────────────────────────────────${NC}
  Verify externally:
    curl -I http://rdmdev-app   (from inside NPM container)
    https://rdmdev.co.za        (from your browser)

  Logs:
    docker logs -f rdmdev-app
    docker logs -f rdmdev-db

  Credentials are in ${INSTALL_DIR}/.env — back them up.
${GREEN}────────────────────────────────────────────────────────${NC}
EOF
