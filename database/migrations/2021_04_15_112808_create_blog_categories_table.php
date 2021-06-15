<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->increments('id');                             // bigIncrements
            $table->integer('parent_id')->unsigned()->default(1); // bigInteger

            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();

            $table->timestamps();  // columns: created_at, updated_at 
            $table->softDeletes(); // columns: deleted_at
                // see columns: \vendor\laravel\framework\src\Illuminate\Database\Schema\Blueprint.php
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_categories');
    }
}
