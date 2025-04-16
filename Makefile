include .env
export $(shell sed 's/=.*//' .env)

# fmt runs php-cs-fixer into docker containter with php-fpm
fmt:
	docker exec -it $(PHP_CONTAINER) ./vendor/bin/php-cs-fixer fix .

up-docker:
	docker-compose --env-file .env -f ./docker/compose.yml up -d

build-compose:
	docker-compose --env-file .env -f ./docker/compose.yml build

down-docker:
	docker-compose --env-file .env -f ./docker/compose.yml down --remove-orphans

# generate-jwt-secret generates jwt secret key with opennsl and writes it into .env file
generate-jwt-secret:
	./scripts/genjwt_secret.sh