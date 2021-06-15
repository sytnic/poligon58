<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // команда php artisan db:seed вызовет последовательно эти сиды и фэкториз
        // для заполнения таблиц
        $this->call(UsersTableSeeder::class);
        $this->call(BlogCategoriesTableSeeder::class);
        factory(\App\Models\BlogPost::class, 100)->create();
    }
}
