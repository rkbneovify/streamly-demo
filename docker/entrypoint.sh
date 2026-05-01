#!/bin/sh
set -eu

# Best-effort permissions (bind mounts on Windows may ignore chown)
mkdir -p storage bootstrap/cache || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R ug+rw storage bootstrap/cache 2>/dev/null || true

exec "$@"

