up:
	docker compose up -d --build

down:
	docker compose down

restart:
	docker compose down && docker compose up -d --build

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

test:
	docker compose exec app php artisan test

lint:
	docker compose exec app ./vendor/bin/pint

logs:
	docker compose logs -f

bash:
	docker compose exec app sh

deploy:
	ssh root@server "cd /var/www/laravel-app && bash deploy/deploy.sh"
