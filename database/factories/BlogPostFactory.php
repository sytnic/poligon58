<?php

use Faker\Generator as Faker;

// Создание фейковых постов
$factory->define(\App\Models\BlogPost::class, function (Faker $faker) {
    $title = $faker->sentence(rand(3, 8), true);  // title
    $txt = $faker->realText(rand(1000, 4000));    // текст
    $isPublished = rand(1, 5) > 1;                // опубликовано, раз в 5 случаях черновик

    $createdAt = $faker->dateTimeBetween('-3 months', '-2 months');

    $data = [
        'category_id' => rand(1, 11),
        'user_id' => (rand(1, 5) == 5) ? 1 : 2,  // редко пост принадлежит 1 пользователю, остальное - 2-ому
        'title' => $title,
        'slug' => str_slug($title),
        'excerpt' => $faker->text(rand(40, 100)), // выдержка для статьи
        'content_raw' => $txt,  // тут будет markdown
        'content_html' => $txt, // тут будет автоматическое создание из markdown
        'is_published' => $isPublished,
        'published_at' => $isPublished ? $faker->dateTimeBetween('-2 month', '-1 days') : null,
            // если опубликовано, то случайная дата в диапазоне, иначе null
        'created_at' => $createdAt,  // дата создания и обновления - раньше, чем дата опубликования,
        'updated_at' => $createdAt,  // задана выше
    ];

    return $data;

});