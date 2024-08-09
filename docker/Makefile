#!make
include .env

container_php       	= ${COMPOSE_PROJECT_NAME}-php-fpm-1
container_postgres    	= ${COMPOSE_PROJECT_NAME}-postgres-1

up: docker-up
down: docker-down
restart: docker-down docker-up
build: docker-build

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down --remove-orphans

docker-pull:
	docker compose pull

docker-build:
	docker compose build

bash:
	docker exec -it $(container_php) bash
psql:
	docker exec -itu postgres $(container_postgres) psql
migrate:
	docker exec -it $(container_php) bin/console doctrine:migration:migrate