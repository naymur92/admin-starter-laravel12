#!/bin/bash
set -e

# ─────────────────────────────────────────────────────────
# Laravel Admin Starter – Docker entrypoint
# Runs before php-fpm starts:
#   1. Wait for database to be reachable
#   2. Restore default public assets from image
#   3. Generate Passport keys if missing
#   4. Set proper ownership and permissions
#   5. Run pending migrations
#   6. Cache config / routes / views for performance
#   7. Create storage symlink
# ─────────────────────────────────────────────────────────

DB_HOST="${DB_HOST:-mysql}"
DB_PORT="${DB_PORT:-3306}"
DB_DATABASE="${DB_DATABASE:-admin_starter}"
DB_USERNAME="${DB_USERNAME:-}"
DB_PASSWORD="${DB_PASSWORD:-}"
DB_CONNECTION="${DB_CONNECTION:-mysql}"

echo "──────────────────────────────────────────"
echo " Laravel Admin Starter – container init"
echo "──────────────────────────────────────────"

# ── 1. Restore default public assets that may be absent in fresh volumes ──
DEFAULTS_SRC="/var/www/html-image-defaults"
if [ -d "$DEFAULTS_SRC" ]; then
    echo "Seeding public assets from image defaults..."

    # Build assets MUST be replaced on every deploy (they contain baked-in
    # environment values and cache-busted filenames). Force-overwrite.
    if [ -d "${DEFAULTS_SRC}/build" ]; then
        rm -rf /var/www/html/public/build
        cp -r "${DEFAULTS_SRC}/build" /var/www/html/public/build
    fi

    # User uploads and other static placeholders: no-clobber to preserve
    # any files uploaded at runtime.
    if [ -d "${DEFAULTS_SRC}/uploads" ]; then
        cp -rn "${DEFAULTS_SRC}/uploads/." /var/www/html/public/uploads/ 2>/dev/null || true
    fi
fi

# Force production/static Vite assets when not in local environment.
if [ "${APP_ENV:-production}" != "local" ]; then
    rm -f /var/www/html/public/hot /var/www/html/storage/framework/vite.hot
fi

# ── 2. Wait for database (MySQL only) ─────────────────────────
if [ "$DB_CONNECTION" = "mysql" ]; then
    echo "Waiting for MySQL database on ${DB_HOST}:${DB_PORT} ..."

    max_attempts=30
    attempt=0
    until php -r "
        \$attempts = 0;
        try {
            new PDO(
                'mysql:host=${DB_HOST};port=${DB_PORT};dbname=${DB_DATABASE}',
                '${DB_USERNAME}',
                '${DB_PASSWORD}',
                [PDO::ATTR_TIMEOUT => 3]
            );
            exit(0);
        } catch (Exception \$e) {
            exit(1);
        }
    " 2>/dev/null; do
        attempt=$((attempt + 1))
        if [ "$attempt" -ge "$max_attempts" ]; then
            echo "ERROR: could not connect to database after ${max_attempts} attempts. Aborting."
            exit 1
        fi
        echo "  → not ready yet (attempt ${attempt}/${max_attempts}), retrying in 3 s ..."
        sleep 3
    done

    echo "Database is ready!"
elif [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "Using SQLite database (no waiting needed)"
fi

# ── 3. Ensure Laravel writable directories exist ────────────
mkdir -p /var/www/html/storage/framework/cache
mkdir -p /var/www/html/storage/framework/sessions
mkdir -p /var/www/html/storage/framework/views
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

# ── 4. Ensure Passport encryption keys exist ─────────────────
if [ ! -f /var/www/html/storage/oauth-private.key ] || [ ! -f /var/www/html/storage/oauth-public.key ]; then
    echo "Generating Passport encryption keys ..."
    php artisan passport:keys --force --no-interaction || true
fi

# ── 5. Set proper ownership and permissions ──────────────────
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

if [ -f /var/www/html/storage/oauth-private.key ]; then
    chown www-data:www-data /var/www/html/storage/oauth-private.key
    chmod 600 /var/www/html/storage/oauth-private.key
fi

if [ -f /var/www/html/storage/oauth-public.key ]; then
    chown www-data:www-data /var/www/html/storage/oauth-public.key
    chmod 600 /var/www/html/storage/oauth-public.key
fi

# ── 6. Run migrations ────────────────────────────────────────
echo "Running database migrations ..."
php artisan migrate --force --no-interaction

# ── 7. Cache configuration ───────────────────────────────────
echo "Caching config / routes / views ..."
php artisan config:cache
php artisan route:cache || true

# Some API-only setups may not have resources/views yet.
if [ -d "/var/www/html/resources/views" ]; then
    php artisan view:cache || true
fi

php artisan event:cache || true

# ── 8. Storage symlink ────────────────────────────────────────
echo "Creating storage symlink ..."
php artisan storage:link --force 2>/dev/null || true

echo "Initialisation complete. Starting PHP-FPM ..."
echo "──────────────────────────────────────────"

# Hand off to CMD (php-fpm)
exec "$@"
