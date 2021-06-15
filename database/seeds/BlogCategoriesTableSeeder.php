<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [];

        $cName = 'Без категории';
        $categories[] = [
            'title' => $cName,
            'slug' => Str::slug($cName),    //The  method generates a URL friendly "slug"
            'parent_id' => 0,
        ];
        // \vendor\laravel\framework\src\Illuminate\Support\helpers.php
        // https://laravel.com/docs/8.x/helpers
        // returns about: bez-kategorii

        // Здесь создаются будущие категории:
        // "Категория #1"
        for($i = 2; $i <= 11; $i++){
            $cName = 'Категория #'.$i;
            $parentId = ($i > 4) ? rand(1,4) : 1; //

            $categories[] = [
                'title' => $cName,
                'slug' => Str::slug($cName), // Str::slug, str_slug устаревшее
                'parent_id' => $parentId,
            ];

        }
        // вставка всех категорий в таблицу blog_categories
        DB::table('blog_categories')->insert($categories);

        // изменять/добавлять записи можно также с помощью модели:
        // \app\Models\BlogCategory.php ,
        // где могут отрабатывать доп.классы (напр, обсерверы(наблюдатели))
    }
}
