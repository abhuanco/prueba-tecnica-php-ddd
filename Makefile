up:
	docker compose up -d --build

install:
	docker exec -it ddd-app composer install

migrate:
	docker exec -it ddd-app ./vendor/bin/doctrine-migrations migrate

down:
	docker compose down -v --rmi all

mysql:
	docker exec -it ddd-mysql mysql -u root -p

test:
	docker exec -it ddd-app vendor/bin/phpunit --testdox
