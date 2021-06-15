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

            $table->boolean('is_published')->default(false); // будет 1 или 0
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // внешние ключи
            $table->foreign('user_id')->references('id')->on('users');
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
