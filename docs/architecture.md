# CRM Architecture

## Backend

Controllers stay thin and delegate domain decisions to `app/Services` and `app/Actions`. Validation lives in Form Requests. Long-running work uses queued jobs backed by Redis.

## Frontend

The frontend uses Inertia pages with feature modules under `resources/js/Features`. Shared UI remains in `resources/js/components`, while cross-cutting stores and utilities live in `resources/js/Stores` and `resources/js/Utils`.

## Data

MySQL is the source of truth. Redis handles cache, queues, and sessions. Dashboard metrics use short-lived Redis caching and can be refreshed without touching the UI layer.

## Production

Production is Dockerized with separate Nginx, PHP-FPM, queue worker, scheduler, Redis, and MySQL services. Node.js is used only during the frontend build stage.

## Future readiness

The schema and directories leave room for multi-tenancy, webhooks, API versioning, object storage, real-time notifications, feature flags, monitoring, and automated backups.
