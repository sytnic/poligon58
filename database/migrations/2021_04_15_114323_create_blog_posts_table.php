<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');                   // bigIncrements, increments was

            $table->bigInteger('category_id')->unsigned(); // bigInteger, integer was
            $table->bigInteger('user_id')->unsigned();     // bigInteger, integer was

            $table->string('slug')->unique();
            $table->string('title');

            $table->text('excerpt')->nullable();

            $table->text('content_raw');
            $table->text('content_html');

            $table->boolean('is_published')->default(false); // будет 1 или 0 в БД
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // внешние ключи
            // до выполнения этого кода в миграциях предварительно должно пройти создание таблиц
            // users и blog_categories
            // наше поле user_id связано с id из таблицы users
            $table->foreign('user_id')->references('id')->on('users');
            // наше поле category_id связано с id из таблицы blog_categories
            $table->foreign('category_id')->references('id')->on('blog_categories');
            
            // индекс
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
