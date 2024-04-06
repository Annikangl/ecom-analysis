#vars
DB_USER=root
DB_PASSWORD=root
DB_NAME=laravel
PHP_CONTAINER=php-fpm

# Setip project
setup: env-prepare create-ssl-dir docker-build composer-install-dev key storage_link migrate seed

env-prepare: # create if not exist env file
	cp -n .env.example .env

key: # generate env APP_KEY
	php artisan key:generate

create-ssl-dir: # create ssl dir
	mkdir $(CURDIR)/docker/nginx/ssl

test: # run tests
	docker compose exec $(PHP_CONTAINER) php artisan test

migrate: # run migrations
	docker compose exec $(PHP_CONTAINER) php artisan migrate

seed: # run seeders
	docker compose exec $(PHP_CONTAINER) php artisan db:seed

storage_link: # create storage symlink
	php artisan storage:link

config-cache-clear: # clear config cache
	php artisan config:clear

migrate-rollback: # rollback migrations
	docker compose exec $(PHP_CONTAINER) php artisan migrate:rollback

migrate-refresh: # refresh migrations
	docker compose exec $(PHP_CONTAINER) php artisan migrate:refresh --seed

composer-install-dev: # install all project dependency
	docker compose exec $(PHP_CONTAINER) composer install

composer-require: # required package
	docker compose exec $(PHP_CONTAINER) composer require $(PACKAGE)

composer-update: # update project dependency
	docker compose exec $(PHP_CONTAINER) composer install

composer-install-prod: # install project dependency for production env
	docker compose exec $(PHP_CONTAINER) composer install --no-dev --optimize-autoloader

docker-build: # build docker
	docker compose up -d --build

docker-up: # up docker
	docker compose up -d

docker-down: # down docker
	docker compose down

dump-db:
	docker compose exec mysql /usr/bin/mysqldump -u $(DB_USER) --password=$(DB_PASSWORD) $(DB_NAME) > backup_`date +'%y.%m.%d %H:%M:%S'`.sql --no-tablespaces

restore-db:
	cat backup_.sql | docker compose exec -T mysql /usr/bin/mysql -u $(USER) --password=$(PASSWORD) $(DB_NAME)
