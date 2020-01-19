.PHONY: help

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

install: ## Install symfony project
	make install-dependencies
	make install-database
	make clear-cache

update: ## Update symfony project
	make install-dependencies
	make update-database
	make clear-cache

install-dependencies: ## Pull master on git, install dependencies and build assets
	composer install
	make build-assets

install-database: ## Rebuild database
#	php bin/console doctrine:database:drop --force
	php bin/console doctrine:database:create
	make update-database
	php bin/console doctrine:fixtures:load --no-interaction --append

update-database: ## Update database
	php bin/console doctrine:migrations:migrate --no-interaction

build-assets: ## Build assets
	yarn install
	yarn encore prod
	php bin/console asset:install --symlink

clear-cache:
	php bin/console cache:clear