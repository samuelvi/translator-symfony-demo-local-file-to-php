# Use -f to specify the path to the docker-compose file
DOCKER_COMPOSE = docker-compose -f docker/docker-compose.yaml

.PHONY: help build up down restart shell composer-install composer-update console rector rector-dry-run test test-coverage qa

help:
	@echo "Available commands:"
	@echo ""
	@echo "Docker commands:"
	@echo "  make build             Build or rebuild the Docker images"
	@echo "  make up                Start the services in the background"
	@echo "  make down              Stop and remove the services"
	@echo "  make restart           Restart the services"
	@echo "  make shell             Access the PHP container shell"
	@echo ""
	@echo "Dependency management:"
	@echo "  make composer-install  Run composer install inside the container"
	@echo "  make composer-update   Run composer update inside the container"
	@echo ""
	@echo "Application:"
	@echo "  make console           Run a Symfony console command (e.g., make console list)"
	@echo "  make demo              Run the translator demo command"
	@echo ""
	@echo "Code quality:"
	@echo "  make rector            Apply Rector code changes"
	@echo "  make rector-dry-run    Check Rector changes without applying"
	@echo ""
	@echo "Testing:"
	@echo "  make test              Run PHPUnit tests"
	@echo "  make test-coverage     Run tests with code coverage report"
	@echo ""
	@echo "Combined:"
	@echo "  make qa                Run all quality checks (rector dry-run + tests)"

build:
	$(DOCKER_COMPOSE) build --no-cache

up:
	$(DOCKER_COMPOSE) up -d

down:
	$(DOCKER_COMPOSE) down

restart: down up

shell:
	$(DOCKER_COMPOSE) exec php-atic-lp bash

composer-install:
	$(DOCKER_COMPOSE) exec php-atic-lp composer install

composer-update:
	$(DOCKER_COMPOSE) exec php-atic-lp composer update

# Allows running any Symfony command, e.g., `make console list` or `make console samuelvi:demo:translator`
console:
	$(DOCKER_COMPOSE) exec php-atic-lp bin/console $(filter-out $@,$(MAKECMDGOALS))

# Run Rector to apply code changes
rector:
	$(DOCKER_COMPOSE) exec php-atic-lp bin/rector process

# Run Rector in dry-run mode to check changes without applying them
rector-dry-run:
	$(DOCKER_COMPOSE) exec php-atic-lp bin/rector process --dry-run

# Run the demo translator command
demo:
	$(DOCKER_COMPOSE) exec php-atic-lp bin/console atico:demo:translator --sheet-name=common --book-name=frontend

# Run PHPUnit tests
test:
	$(DOCKER_COMPOSE) exec php-atic-lp bin/phpunit

# Run tests with code coverage
test-coverage:
	$(DOCKER_COMPOSE) exec php-atic-lp bin/phpunit --coverage-html var/coverage

# Run all quality checks
qa: rector-dry-run test

# This is needed to pass arguments to the console command
%:
	@: