#!/usr/bin/env bash
set -Eeuo pipefail

APP_DIR="${APP_DIR:-/var/www/school_management}"
BACKUP_DIR="${BACKUP_DIR:-/var/backups/school_management}"
RETENTION_DAYS="${RETENTION_DAYS:-14}"

cd "$APP_DIR"
mkdir -p "$BACKUP_DIR"
timestamp="$(date -u +%Y%m%dT%H%M%SZ)"

case "${DB_CONNECTION:-}" in
    mysql)
        : "${DB_HOST:?DB_HOST is required}"
        : "${DB_DATABASE:?DB_DATABASE is required}"
        : "${DB_USERNAME:?DB_USERNAME is required}"
        : "${DB_PASSWORD:?DB_PASSWORD is required}"
        MYSQL_PWD="$DB_PASSWORD" mysqldump --single-transaction --quick --routines --triggers \
            -h "$DB_HOST" -P "${DB_PORT:-3306}" -u "$DB_USERNAME" "$DB_DATABASE" \
            | gzip > "$BACKUP_DIR/database-$timestamp.sql.gz"
        ;;
    sqlite)
        : "${DB_DATABASE:?DB_DATABASE is required}"
        sqlite3 "$DB_DATABASE" ".backup '$BACKUP_DIR/database-$timestamp.sqlite'"
        gzip "$BACKUP_DIR/database-$timestamp.sqlite"
        ;;
    *)
        echo "Unsupported DB_CONNECTION: ${DB_CONNECTION:-unset}" >&2
        exit 1
        ;;
esac

tar -czf "$BACKUP_DIR/storage-$timestamp.tar.gz" -C "$APP_DIR" storage/app
find "$BACKUP_DIR" -type f -mtime +"$RETENTION_DAYS" -delete
