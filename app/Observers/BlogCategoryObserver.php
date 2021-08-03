<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogCategoryObserver
{
    /**
     * Нижеследующие методы наблюдают за событиями "после" их совершения.
     */

    /**
     * Handle the models blog category "created" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     */
    public function created(BlogCategory $modelsBlogCategory)
    {
        //
    }


     /**
     * @param BlogCategory $blogCategory
     */
    public function creating(BlogCategory $blogCategory)
    {
        $this->setSlug($blogCategory);
    }

    /**
     * Если поле слаг пустое, то заполняем его конвертацией с заголовка
     *
     * @param BlogCategory $blogCategory
     */
    protected function setSlug(BlogCategory $blogCategory)
    {
        if(empty($blogCategory->slug)){
            $blogCategory->slug = Str::slug($blogCategory->title);
        }
    }

    /**
     * Событие происходит до обновления записи в БД.
     * @param BlogCategory $blogCategory
     */
    public function updating(BlogCategory $blogCategory)
    {
       // dd(__METHOD__,  $blogCategory->getDirty());
       
        $this->setSlug($blogCategory);
    }


    /**
     * Handle the models blog category "updated" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     */
    public function updated(BlogCategory $modelsBlogCategory)
    {
        //
    }

    /**
     * Handle the models blog category "deleted" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     * "Мягкое" удаление, не из БД.
     */
    public function deleted(BlogCategory $modelsBlogCategory)
    {
        //
    }

    /**
     * Handle the models blog category "restored" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     */
    public function restored(BlogCategory $modelsBlogCategory)
    {
        //
    }

    /**
     * Handle the models blog category "force deleted" event.
     *
     * @param  \App\Models\BlogCategory  $modelsBlogCategory
     * @return void
     * "Жесткое" удаление, из БД.
     */
    public function forceDeleted(BlogCategory $modelsBlogCategory)
    {
        //
    }
}
