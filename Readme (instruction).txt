1. buat database berdasarkan data yang berasal dari .env (DB_DATABASE)
2. lakukan command berikut ini : 
php artisan config:clear
php artisan cache:clear
php artisan config:clear
php artisan key:generate
php artisan migrate:fresh
php artisan db:seed
php artisan serve