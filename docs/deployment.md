# Deployment

1. Build dependencies and assets in CI.
2. Run Pint, PHPStan, ESLint, TypeScript, and tests.
3. Build production Docker images.
4. Push images to a registry in your deployment pipeline.
5. Deploy `nginx`, `php-fpm`, `queue-worker`, `scheduler`, `redis`, and `mysql`.
6. Run `php artisan migrate --force`.
7. Run `php artisan queue:restart`.
8. Check `/up` or container health checks before shifting traffic.

Never commit real `.env.production` secrets. Use your platform's secret manager or encrypted environment configuration.
