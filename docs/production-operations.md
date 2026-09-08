# Production operations

The application must be configured with secrets on the server; do not commit a
production `.env` file or Firebase service-account JSON.

## Initial deployment

```bash
cp .env.example .env
php artisan key:generate --force
php artisan storage:link
php artisan migrate --force
php artisan optimize
```

Set `APP_ENV=production`, `APP_DEBUG=false`, a HTTPS `APP_URL`, the production
database values, `CORS_ALLOWED_ORIGINS`, and matching
`SANCTUM_STATEFUL_DOMAINS`. Place the Firebase service-account file at the path
configured by `FIREBASE_CREDENTIALS_PATH` and restrict it to the application
user (`chmod 640`).

## Queue worker

Copy `ops/supervisor/school-management-worker.conf` to
`/etc/supervisor/conf.d/`, update `directory` if necessary, then run:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart school-management-worker
```

The worker must be monitored and restarted after every deployment:

```bash
php artisan queue:failed
php artisan queue:retry all
```

## Scheduler

Copy `ops/cron/school-management` to `/etc/cron.d/school-management`. The
Library overdue command is registered by the module and runs daily through
Laravel Scheduler:

```bash
php artisan schedule:list
php artisan schedule:run
```

## Backups and monitoring

Run `ops/backup/backup.sh` daily from a protected cron job, set `BACKUP_DIR` to
durable storage, and copy backups off-host. Test restoration regularly; a
backup is not considered valid until a restore has succeeded.

Forward `storage/logs/laravel.log` and the Supervisor worker log to the
monitoring system. Alert on failed queue jobs, repeated worker restarts, HTTP
5xx responses, disk usage, database backup failures, and expired TLS
certificates.
