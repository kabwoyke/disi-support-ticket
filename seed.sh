#!/usr/bin

php artisan migrate:fresh

php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=DepartmentSeeder
php artisan db:seed --class=EquipmentSeeder
php artisan db:seed --class=SupportTeamSeeder
