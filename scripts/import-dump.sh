#!/usr/bin/env sh
set -eu

script_dir=$(CDPATH='' cd -- "$(dirname -- "$0")" && pwd)
project_dir=$(CDPATH='' cd -- "$script_dir/../.." && pwd)
cd "$project_dir"
dump_path=${1:-Finance/finance_2026-10-24.sql}
[ -s "$dump_path" ] || { echo "Dump missing or empty: $dump_path" >&2; exit 1; }
# This import is explicitly scoped to finance. Reject dumps selecting another DB.
if LC_ALL=C grep -Eiq '^[[:space:]]*(USE[[:space:]]|CREATE[[:space:]]+DATABASE|DROP[[:space:]]+DATABASE)' "$dump_path"; then
    echo 'Dump contains database-selection statements; review before importing.' >&2
    exit 1
fi

docker compose exec -T mysql sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -uroot -e "SELECT 1"' >/dev/null
backup_dir=Finance/storage/app/db-backups
mkdir -p "$backup_dir"
chmod 700 "$backup_dir"
backup_path="$backup_dir/finance-before-import-$(date +%Y%m%d-%H%M%S)-$$.sql"
umask 077
exists=$(docker compose exec -T mysql sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -uroot -N -e "SELECT COUNT(*) FROM information_schema.SCHEMATA WHERE SCHEMA_NAME = 0x66696e616e6365"')
if [ "$exists" = 1 ]; then
    if docker compose exec -T mysql sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" exec mysqldump -uroot --single-transaction --routines --triggers --events finance' > "$backup_path.partial"; then
        mv "$backup_path.partial" "$backup_path"
        echo "Backup saved: $backup_path"
    else
        rm -f "$backup_path.partial"
        echo 'Backup failed. Database was not modified.' >&2
        exit 1
    fi
else
    docker compose exec -T mysql sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -uroot -e "CREATE DATABASE finance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"'
fi

docker compose exec -T mysql sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" exec mysql -uroot --default-character-set=utf8mb4 finance' < "$dump_path"
docker compose exec -T mysql sh -c 'MYSQL_PWD="$MYSQL_ROOT_PASSWORD" mysql -uroot finance -e "SELECT (SELECT COUNT(*) FROM users) AS users, (SELECT COUNT(*) FROM years) AS years, (SELECT COUNT(*) FROM months) AS months, (SELECT COUNT(*) FROM transactions) AS transactions, (SELECT COUNT(*) FROM tasks) AS tasks"'
echo 'Import complete.'
