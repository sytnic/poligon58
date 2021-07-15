<?php

namespace App\Observers;

use App\Models\BlogCategory;
use Carbon\Carbon;

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
