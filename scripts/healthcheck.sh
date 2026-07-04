#!/usr/bin/env sh
set -eu
php artisan about --only=environment >/dev/null
php artisan route:list --except-vendor >/dev/null
