# Operations Runbook

## Local setup

```bash
cp .env.development .env
make docker-up
make install
php artisan key:generate
php artisan migrate --seed
make dev
```

## Production release

```bash
make prod-build
make prod-up
docker compose --env-file .env.production -f docker/prod/compose.yml exec php-fpm php artisan migrate --force
docker compose --env-file .env.production -f docker/prod/compose.yml exec php-fpm php artisan queue:restart
```

## Backups

Use `make backup` for local database backups. In production, schedule managed MySQL backups or run `mysqldump` from a secured maintenance container with encrypted storage.

## Health checks

The PHP image includes a container health check. CI and deployment platforms should also verify Laravel's `/up` endpoint and queue worker freshness before promoting a release.
