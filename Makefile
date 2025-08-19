dev:
	npm run dev

up:
	./vendor/bin/sail up -d

down:
	./vendor/bin/sail down

stop:
	./vendor/bin/sail stop

restart:
	./vendor/bin/sail restart

migrate:
	./vendor/bin/sail php artisan migrate

migrate-rollback:
	./vendor/bin/sail php artisan migrate:rollback

fresh:
	./vendor/bin/sail php artisan migrate:fresh --seed

seed:
	./vendor/bin/sail php artisan db:seed

dumpautoload:
	./vendor/bin/sail composer dumpautoload

config-create:
	./vendor/bin/sail php artisan make:app-settings-tab

composer-install:
	./vendor/bin/sail composer install
