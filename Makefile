.PHONY: build up down shell test cs-fix analyze deptrac

build:
	docker-compose build

up:
	docker-compose up -d

down:
	docker-compose down

shell:
	docker-compose exec app sh

init: build up
	docker-compose exec app composer install

test:
	docker-compose exec app vendor/bin/phpunit tests

cs-fix:
	docker-compose exec app vendor/bin/php-cs-fixer fix --allow-risky=yes

analyze:
	docker-compose exec app vendor/bin/phpstan analyse --memory-limit=1G

deptrac:
	docker-compose exec app vendor/bin/deptrac

rector:
	docker-compose exec app vendor/bin/rector process
