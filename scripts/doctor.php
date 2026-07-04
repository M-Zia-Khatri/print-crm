<?php

$env = file_exists(__DIR__.'/../.env') ? parse_ini_file(__DIR__.'/../.env', false, INI_SCANNER_RAW) : [];
$connection = $env['DB_CONNECTION'] ?? getenv('DB_CONNECTION') ?: 'sqlite';
$required = [
    'mysql' => 'pdo_mysql',
    'mariadb' => 'pdo_mysql',
    'sqlite' => 'pdo_sqlite',
    'pgsql' => 'pdo_pgsql',
    'sqlsrv' => 'pdo_sqlsrv',
];
$extension = $required[$connection] ?? null;

if ($extension && ! extension_loaded($extension)) {
    fwrite(STDERR, "Missing PHP extension [{$extension}] for DB_CONNECTION={$connection}.\n");
    fwrite(STDERR, "Install/enable the extension, restart PHP, then rerun the command. For local MySQL development, copy .env.development to .env only after pdo_mysql is available.\n");
    exit(1);
}

if ($connection === 'sqlite') {
    $database = $env['DB_DATABASE'] ?? 'database/database.sqlite';

    if ($database !== ':memory:') {
        $path = str_starts_with($database, DIRECTORY_SEPARATOR) ? $database : __DIR__.'/../'.$database;
        $directory = dirname($path);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (! file_exists($path)) {
            touch($path);
        }
    }
}

echo "Environment requirements OK for DB_CONNECTION={$connection}.\n";
