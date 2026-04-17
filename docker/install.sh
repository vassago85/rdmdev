#!/usr/bin/env bash
#
# RDM Developments — one-shot production installer.
#
# Idempotent: safe to re-run. It will:
#   1. Ensure /opt/rdmdev exists and is owned by the current user
#   2. Clone the repo (or pull if already present)
#   3. Create .env from docker/env.production.example (only if missing)
#      and fill in strong random DB passwords
#   4. Build the Docker image
#   5. Start the stack (db, app, scheduler)
#   6. Wait for the app container to become healthy
#   7. Create a Filament admin user (unless --skip-admin is passed)
#
# Usage:
#   curl -fsSL https://raw.githubusercontent.com/vassago85/rdmdev/main/docker/install.sh | bash
#   # OR, after clone:
#   bash /opt/rdmdev/docker/install.sh
#
# Options (env vars):
#   REPO_URL   - override git remote (default: https://github.com/vassago85/rdmdev.git)
#   INSTALL_DIR - override install path (default: /opt/rdmdev)
#   BRANCH     - git branch (default: main)
#   ADMIN_NAME / ADMIN_EMAIL / ADMIN_PASSWORD - non-interactive admin creation
#
# Flags:
#   --skip-admin   - don't prompt for admin user (create later with make:filament-user)
#   --skip-build   - skip docker build (use existing image)
#   --help         - show this help
set -euo pipefail

#---------- Config ----------
REPO_URL="${REPO_URL:-https://github.com/vassago85/rdmdev.git}"
INSTALL_DIR="${INSTALL_DIR:-/opt/rdmdev}"
BRANCH="${BRANCH:-main}"
COMPOSE_FILE="docker-compose.prod.yml"

SKIP_ADMIN=0
SKIP_BUILD=0
for arg in "$@"; do
    case "$arg" in
        --skip-admin) SKIP_ADMIN=1 ;;
        --skip-build) SKIP_BUILD=1 ;;
        --help|-h)
            sed -n '2,30p' "$0"
            exit 0
            ;;
        *) echo "Unknown argument: $arg" >&2; exit 1 ;;
    esac
done

#---------- Output helpers ----------
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

step()    { echo -e "\n${BLUE}==>${NC} ${1}"; }
success() { echo -e "${GREEN}✓${NC} ${1}"; }
warn()    { echo -e "${YELLOW}⚠${NC} ${1}"; }
err()     { echo -e "${RED}✗${NC} ${1}" >&2; }

#---------- Pre-flight ----------
step "Pre-flight checks"

if [ "$EUID" -eq 0 ]; then
    err "Don't run this script as root. Run as your regular user (it will sudo where needed)."
    exit 1
fi

command -v git >/dev/null || { err "git is required but not installed"; exit 1; }
command -v docker >/dev/null || { err "docker is required but not installed"; exit 1; }
docker compose version >/dev/null 2>&1 || { err "docker compose v2 is required (got older 'docker-compose'?)"; exit 1; }

if ! docker ps >/dev/null 2>&1; then
    err "Current user can't talk to docker. Add yourself to the docker group:"
    err "  sudo usermod -aG docker \$USER && newgrp docker"
    exit 1
fi

if ! docker network inspect nginx-proxy-manager_default >/dev/null 2>&1; then
    warn "Network 'nginx-proxy-manager_default' not found."
    warn "The stack expects Nginx Proxy Manager to be running on this host."
    warn "Continuing anyway — docker compose will fail if the network isn't created by the time 'up' runs."
fi

success "Pre-flight OK"

#---------- Directory + code ----------
step "Preparing ${INSTALL_DIR}"

if [ ! -d "$INSTALL_DIR" ]; then
    sudo mkdir -p "$INSTALL_DIR"
fi
sudo chown -R "$USER:$USER" "$INSTALL_DIR"

cd "$INSTALL_DIR"

if [ -d .git ]; then
    step "Updating existing clone"
    git fetch origin "$BRANCH"
    git checkout "$BRANCH"
    git pull --ff-only origin "$BRANCH"
    success "Repo updated to latest ${BRANCH}"
else
    step "Cloning ${REPO_URL}"
    # Clone into current (empty) dir; if dir has contents, bail out clearly
    if [ -n "$(ls -A . 2>/dev/null)" ]; then
        err "${INSTALL_DIR} is not empty and not a git repo. Move or remove its contents and re-run."
        exit 1
    fi
    git clone --branch "$BRANCH" "$REPO_URL" .
    success "Cloned ${BRANCH}"
fi

#---------- .env ----------
step "Configuring .env"

if [ ! -f .env ]; then
    cp docker/env.production.example .env
    success "Created .env from template"

    DB_PASS=$(openssl rand -base64 32 | tr -d '=+/' | cut -c1-32)
    DB_ROOT_PASS=$(openssl rand -base64 32 | tr -d '=+/' | cut -c1-32)

    sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASS}|"            .env
    sed -i "s|^DB_ROOT_PASSWORD=.*|DB_ROOT_PASSWORD=${DB_ROOT_PASS}|" .env

    success "Generated random DB passwords"
    warn   "Review .env now and set MAIL_* / APP_URL / etc. if needed."
    warn   "Credentials saved only in ${INSTALL_DIR}/.env — back them up!"
    # Mode 666 (not 600) because the app container bind-mounts this file and
    # runs as a UID not in the 'paul' group, so 'other' write permission is
    # required for the entrypoint to persist APP_KEY on first boot. The parent
    # /opt/rdmdev/ directory is owned by the deploy user with restrictive
    # permissions, so this file is still only reachable by the deploy user.
    chmod 666 .env
else
    success ".env already exists (leaving untouched)"
    # Ensure container can still write to it (idempotent guard for existing installs)
    current_mode=$(stat -c '%a' .env 2>/dev/null || echo "000")
    if [ "$current_mode" != "666" ]; then
        warn "Setting .env permissions to 666 so the app container can persist APP_KEY"
        chmod 666 .env
    fi
fi

# Sanity-check required env vars are set
if ! grep -qE '^DB_PASSWORD=.+' .env || grep -q '^DB_PASSWORD=CHANGE_ME' .env; then
    err "DB_PASSWORD is not set in .env"
    exit 1
fi
if ! grep -qE '^DB_ROOT_PASSWORD=.+' .env || grep -q '^DB_ROOT_PASSWORD=CHANGE_ME' .env; then
    err "DB_ROOT_PASSWORD is not set in .env"
    exit 1
fi

#---------- Build ----------
if [ "$SKIP_BUILD" -eq 0 ]; then
    step "Building Docker image (this may take several minutes on first run)"
    docker compose -f "$COMPOSE_FILE" build
    success "Image built"
else
    warn "Skipping docker build (--skip-build)"
fi

#---------- Up ----------
step "Starting containers"
docker compose -f "$COMPOSE_FILE" up -d
success "Containers started"

#---------- Wait for health ----------
step "Waiting for rdmdev-app to become healthy"
max_wait=180
elapsed=0
while [ $elapsed -lt $max_wait ]; do
    status=$(docker inspect --format='{{.State.Health.Status}}' rdmdev-app 2>/dev/null || echo "missing")
    case "$status" in
        healthy)
            success "rdmdev-app is healthy"
            break
            ;;
        unhealthy)
            err "rdmdev-app reported unhealthy. Recent logs:"
            docker logs rdmdev-app --tail 40
            exit 1
            ;;
    esac
    sleep 3
    elapsed=$((elapsed + 3))
    printf "."
done
echo ""

if [ "$status" != "healthy" ]; then
    warn "rdmdev-app did not become healthy within ${max_wait}s (status: ${status})."
    warn "Check: docker logs rdmdev-app --tail 100"
fi

#---------- Admin user ----------
if [ "$SKIP_ADMIN" -eq 1 ]; then
    warn "Skipping admin user creation (--skip-admin)."
    warn "Create one later with:"
    warn "  docker compose -f $COMPOSE_FILE exec app php artisan make:filament-user"
else
    step "Creating Filament admin user"

    # Check if any user already exists (avoid accidental re-prompt)
    user_count=$(docker compose -f "$COMPOSE_FILE" exec -T app php artisan tinker --execute="echo \\App\\Models\\User::count();" 2>/dev/null | tail -1 | tr -d '\r\n ' || echo "0")

    if [ "$user_count" != "0" ] && [ -n "$user_count" ]; then
        success "A user already exists in the database (count: ${user_count}). Skipping."
    elif [ -n "${ADMIN_NAME:-}" ] && [ -n "${ADMIN_EMAIL:-}" ] && [ -n "${ADMIN_PASSWORD:-}" ]; then
        docker compose -f "$COMPOSE_FILE" exec -T app php artisan make:filament-user \
            --name="$ADMIN_NAME" --email="$ADMIN_EMAIL" --password="$ADMIN_PASSWORD"
        success "Admin user created: ${ADMIN_EMAIL}"
    else
        echo "Enter admin details (you can change the password from the UI later):"
        docker compose -f "$COMPOSE_FILE" exec app php artisan make:filament-user
    fi
fi

#---------- Summary ----------
step "Deployment complete"

docker compose -f "$COMPOSE_FILE" ps

cat <<EOF

${GREEN}────────────────────────────────────────────────────────${NC}
  Next steps:

  1. Point DNS:
       A  rdmdev.co.za      -> 41.72.157.26
       A  www.rdmdev.co.za  -> 41.72.157.26

  2. Configure Nginx Proxy Manager proxy host:
       Domain:            rdmdev.co.za (+ www.rdmdev.co.za)
       Forward hostname:  rdmdev-app
       Forward port:      80
       Scheme:            http
       Block Common Exploits + Websockets: on
       SSL:               Let's Encrypt, Force SSL, HTTP/2

  3. Visit:
       https://rdmdev.co.za
       https://rdmdev.co.za/admin

  Useful commands:
    docker logs -f rdmdev-app
    docker compose -f $COMPOSE_FILE exec app sh
    docker compose -f $COMPOSE_FILE exec app php artisan migrate --force

  Pull & rebuild for updates:
    cd $INSTALL_DIR && git pull && docker compose -f $COMPOSE_FILE build --no-cache app && docker compose -f $COMPOSE_FILE up -d --force-recreate app scheduler
${GREEN}────────────────────────────────────────────────────────${NC}
EOF
