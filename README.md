# Print CRM

Production-ready Laravel, Inertia, React, TypeScript, MySQL, and Redis CRM foundation.

## Stack

- Laravel 13 / PHP 8.4
- Inertia.js, React 19, TypeScript, Vite, Tailwind CSS v4, shadcn/ui
- MySQL 8.4 and Redis 7.4
- Docker Compose for development infrastructure
- Multi-stage production Docker with Nginx, PHP-FPM, queue worker, scheduler, Redis, and MySQL
- pnpm for frontend dependencies

## Development

Docker runs infrastructure only. Run PHP, Composer, pnpm, Laravel, and Vite on your host.

```bash
cp .env.development .env
make docker-up
make install
php artisan key:generate
php artisan migrate
make dev
```

## Production

Production runs fully in Docker. Update `.env.production` with real secrets before deployment.

```bash
make prod-build
make prod-up
```

## Project structure

- `app/Actions`, `app/Services`, `app/DTOs`, and related directories prepare the backend for thin controllers and focused domain logic.
- `resources/js/Features` contains feature modules.
- `docker/dev` contains development-only MySQL and Redis services.
- `docker/prod` contains production build and runtime definitions.
- `nginx` contains production web server configuration.
- `docs` contains phase-by-phase architecture notes.
