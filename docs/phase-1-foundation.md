# Phase 1 — Project Foundation

## Design decisions

- Laravel remains the application runtime and owns routing, authentication, queues, scheduling, and persistence.
- React, TypeScript, Inertia, Vite, Tailwind CSS, and shadcn/ui remain the frontend foundation.
- Development Docker is intentionally limited to MySQL and Redis so PHP, Composer, pnpm, and Vite run natively on the host.
- Production Docker builds frontend assets in a Node build stage, installs Composer dependencies in a Composer stage, and runs only PHP-FPM, Nginx, queue, scheduler, MySQL, and Redis at runtime.
- Redis is the default cache, queue, and session backend to match the production architecture from day one.
- Empty application directories are tracked with `.gitkeep` files so future phases can add Actions, DTOs, Services, Jobs, Policies, and feature modules without reorganizing the codebase.

## Local development

```bash
cp .env.development .env
make docker-up
make install
php artisan key:generate
php artisan migrate
make dev
```

## Production preview

```bash
cp .env.production .env.production.local
# edit secrets before deploying
make prod-build
make prod-up
```
