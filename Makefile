.PHONY: up down shell composer build

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

shell:
	docker-compose exec php bash

composer:
	docker-compose exec php composer $(filter-out $@,$(MAKECMDGOALS))

