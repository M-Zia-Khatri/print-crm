# Print CRM

Production-ready Laravel + Inertia + React scaffold for local host-based development and Dockerized production deployment.

## Stack

- Laravel 12 on PHP 8.4
- Inertia.js + React + TypeScript + Vite
- Tailwind CSS v4 and shadcn/ui-compatible component setup
- MySQL 8.4 and Redis 8
- Production Nginx + PHP-FPM containers
- Horizon, Pulse, Telescope, Debugbar, Spatie packages, Intervention Image

## Environment files

| File | Purpose |
| --- | --- |
| `.env` | Local host development. Laravel runs on the host and connects to forwarded Docker ports on `127.0.0.1`. |
| `.env.example` | Safe defaults for fresh clones. Copy to `.env` when needed. |
| `.env.docker` | Container-to-container local values using service names such as `mysql` and `redis`. |
| `.env.production` | Production Compose values using service names. Replace placeholders before deployment and generate a real `APP_KEY`. |

No real secrets are committed. Update `APP_KEY`, database passwords, mail settings, and `APP_URL` for deployed environments.

## Local development workflow

The development Compose file starts infrastructure only: MySQL and Redis. PHP and Vite run on the host.

```bash
make composer
make npm
php artisan key:generate
make up
make migrate
php artisan serve
npm run dev
```

Useful commands:

```bash
make up          # start MySQL + Redis dev containers
make down        # stop dev containers
make restart     # restart dev containers
make mysql       # open mysql client in the dev mysql container
make redis       # open redis-cli in the dev redis container
make migrate     # run migrations on the host
make fresh       # rebuild the local database schema
make seed        # run configured seeders
make test        # run Laravel tests
make pint        # format PHP with Pint
make format      # format frontend resources
make logs        # follow dev container logs
make cache-clear # clear Laravel caches
make optimize    # cache optimized Laravel bootstrap files
```

## Production Docker workflow

The production Compose file builds production assets, installs optimized Composer dependencies, and runs Nginx, PHP-FPM, MySQL, Redis, a Horizon queue worker, and a scheduler loop.

```bash
cp .env.production .env.production.local # optional external secret management file
php artisan key:generate --show          # copy the generated key into production secrets
make prod-up
```

Production services communicate by Docker service name (`mysql`, `redis`, `app`) rather than loopback addresses.

Production helpers:

```bash
make prod-build
make prod-up
make prod-down
make prod-logs
make prod-shell
```

## Package configuration

Package configuration files are present under `config/` with conservative defaults. Migration files for third-party packages are intentionally not generated in this setup-only scaffold; publish and review package migrations deliberately when the application data model is ready.

## Notes

- The development database is exposed on `${FORWARD_DB_PORT:-3306}` and Redis on `${FORWARD_REDIS_PORT:-6379}`.
- The production PHP image explicitly installs `pdo_mysql` and the PECL `redis` extension.
- The production build runs `npm run build` so Vite assets are available to Nginx.
