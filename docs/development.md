# Development Workflow

`make dev` intentionally avoids Laravel Pail so it works on Windows and other PHP installations where the `pcntl` extension is unavailable.

The default dev process starts three long-running commands through `concurrently`:

```bash
php artisan serve --host=localhost
php artisan queue:listen --tries=1 --timeout=0
pnpm dev
```

If your PHP runtime supports `pcntl` and you want Laravel Pail log streaming, use:

```bash
make dev-pail
```

On Windows, `pcntl` is typically unavailable. In that case, use `make dev` and inspect logs with standard Laravel log files under `storage/logs`.
