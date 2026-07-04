# Local Database Setup

The repository keeps `.env.example` on SQLite so a fresh clone can run `php artisan migrate` even when the host PHP installation does not have the MySQL PDO driver enabled.

For MySQL development, install and enable the host PHP `pdo_mysql` extension first, then use the Docker infrastructure template:

```bash
cp .env.development .env
make docker-up
make doctor
php artisan migrate
```

If you see `could not find driver` with `DB_CONNECTION=mysql`, PHP is missing `pdo_mysql`; MySQL itself may be running correctly, but PHP cannot connect to it until the extension is enabled.

Common fixes:

- Ubuntu/Debian: `sudo apt install php8.4-mysql`
- macOS Homebrew PHP: ensure `pdo_mysql` is listed by `php -m`
- Windows: enable `extension=pdo_mysql` in the active `php.ini`

Run `make doctor` to verify the active `.env` database driver before migrations.
