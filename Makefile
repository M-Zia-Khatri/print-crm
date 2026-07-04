SHELL := /bin/bash
DEV_COMPOSE := docker compose --env-file .env -f docker/dev/compose.yml
PROD_COMPOSE := docker compose --env-file .env.production -f docker/prod/compose.yml

.PHONY: install setup doctor dev dev-pail stop restart logs shell clean artisan migrate migrate-fresh seed fresh optimize optimize-clear cache route-cache config-cache view-cache pnpm-install pnpm-dev pnpm-build lint format docker-up docker-down docker-build docker-rebuild docker-prune prod-build prod-up prod-down prod-restart db backup restore queue queue-restart schedule

install:
	composer install
	pnpm install

setup: install
	cp -n .env.example .env || true
	php scripts/doctor.php
	php artisan key:generate
	php artisan migrate --force
	pnpm build

doctor:
	php scripts/doctor.php

dev: doctor
	composer run dev

dev-pail: doctor
	composer run dev:pail

stop: docker-down
restart: docker-down docker-up
logs:
	$(DEV_COMPOSE) logs -f
shell:
	bash
clean:
	php artisan optimize:clear
	rm -rf node_modules public/build

artisan:
	php artisan $(filter-out $@,$(MAKECMDGOALS))
migrate: doctor
	php artisan migrate
migrate-fresh: doctor
	php artisan migrate:fresh
seed:
	php artisan db:seed
fresh: doctor
	php artisan migrate:fresh --seed
optimize:
	php artisan optimize
optimize-clear:
	php artisan optimize:clear
cache:
	php artisan cache:clear
route-cache:
	php artisan route:cache
config-cache:
	php artisan config:cache
view-cache:
	php artisan view:cache

pnpm-install:
	pnpm install
pnpm-dev:
	pnpm dev
pnpm-build:
	pnpm build
lint:
	composer run lint:check && pnpm lint:check
format:
	composer run lint && pnpm format

docker-up:
	$(DEV_COMPOSE) up -d
docker-down:
	$(DEV_COMPOSE) down
docker-build:
	$(DEV_COMPOSE) build
docker-rebuild:
	$(DEV_COMPOSE) build --no-cache
docker-prune:
	docker system prune -f

prod-build:
	$(PROD_COMPOSE) build
prod-up:
	$(PROD_COMPOSE) up -d
prod-down:
	$(PROD_COMPOSE) down
prod-restart: prod-down prod-up

db:
	$(DEV_COMPOSE) exec mysql mysql -u$${DB_USERNAME:-print_crm} -p$${DB_PASSWORD:-secret} $${DB_DATABASE:-print_crm}
backup:
	mkdir -p storage/backups
	$(DEV_COMPOSE) exec mysql mysqldump -u$${DB_USERNAME:-print_crm} -p$${DB_PASSWORD:-secret} $${DB_DATABASE:-print_crm} > storage/backups/$$(date +%Y%m%d%H%M%S).sql
restore:
	@test -n "$(file)" || (echo "Usage: make restore file=storage/backups/backup.sql" && exit 1)
	$(DEV_COMPOSE) exec -T mysql mysql -u$${DB_USERNAME:-print_crm} -p$${DB_PASSWORD:-secret} $${DB_DATABASE:-print_crm} < $(file)

queue:
	php artisan queue:work redis
queue-restart:
	php artisan queue:restart
schedule:
	php artisan schedule:work
%:
	@:
