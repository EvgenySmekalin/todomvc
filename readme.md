# Quick start

1. Clone repository to your disk
```
git clone https://github.com/EvgenySmekalin/todomvc.git
```
2. Change directory to todomvc and install composer dependencies 
```
cd todomvc
composer install
```
3. Create copy file .env.example and rename it to .env
```
cp .env.example .env
```
4. Run command 
```
php artisan key:generate
```
5. Config database: change DB_* params in .env file
6. Run migrations
```
php artisan migrate
```
