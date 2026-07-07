DEV_COMPOSE=docker compose --env-file .env -f docker/development/docker-compose.yml
PROD_COMPOSE=docker compose --env-file .env.production -f docker/production/docker-compose.yml
ARTISAN=php artisan

.PHONY: up down restart mysql redis migrate fresh seed test pint format composer npm logs shell cache-clear optimize prod-up prod-down prod-build prod-logs prod-shell

up:
	$(DEV_COMPOSE) up -d

down:
	$(DEV_COMPOSE) down

restart: down up

mysql:
	$(DEV_COMPOSE) exec mysql mysql -u$${DB_USERNAME:-print_crm} -p$${DB_PASSWORD:-password} $${DB_DATABASE:-print_crm}

redis:
	$(DEV_COMPOSE) exec redis redis-cli

migrate:
	$(ARTISAN) migrate

fresh:
	$(ARTISAN) migrate:fresh

seed:
	$(ARTISAN) db:seed

test:
	$(ARTISAN) test

pint:
	./vendor/bin/pint

format:
	npm run format

composer:
	composer install

npm:
	npm install

logs:
	$(DEV_COMPOSE) logs -f

shell:
	bash

cache-clear:
	$(ARTISAN) optimize:clear

optimize:
	$(ARTISAN) optimize

prod-build:
	$(PROD_COMPOSE) build

prod-up:
	$(PROD_COMPOSE) up -d --build

prod-down:
	$(PROD_COMPOSE) down

prod-logs:
	$(PROD_COMPOSE) logs -f

prod-shell:
	$(PROD_COMPOSE) exec app sh
