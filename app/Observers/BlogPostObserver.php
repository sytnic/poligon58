<?php

namespace App\Observers;

use App\Models\BlogPost;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogPostObserver
{

    /**
     * Обработка ПЕРЕД созданием записи
     *
     * @param BlogPost $blogPost
     */
    public function creating(BlogPost $blogPost)
    {
        $this->setPublishedAt($blogPost);

        $this->setSlug($blogPost);

        $this->setHTML($blogPost);

        $this->setUser($blogPost);
    }

    /**
     * Обработка ПЕРЕД обновлением записи
     *
     * @param BlogPost $blogPost
     */
    public function updating(BlogPost $blogPost)
    {
//        Некоторые полезные методы в обсерверах

/*        $test[] = $blogPost->isDirty();   // если хотя бы одно поле в БД изменится, вернётся true
       $test[] = $blogPost->isDirty('is_published');      // проверка не всех, а одного поля
       $test[] = $blogPost->isDirty('user_id');           // то же самое
       $test[] = $blogPost->getDirty();                   // выдаст массив со списком полей, которые будут изменены
       $test[] = $blogPost->getAttribute('is_published'); // "пойманное" значение, к-рое будет записано в БД
       $test[] = $blogPost->is_published;                 // то же самое
       $test[] = $blogPost->getOriginal('is_published');  // значение из БД до изменения
       dd($test);
 */
        $this->setPublishedAt($blogPost);

        $this->setSlug($blogPost);

    //    return false; // так можно вызвать ошибку сохранения при галочке "Опубликовано" или нет
    }
    /**
     *  Если публикация не установлена и происходит установка флага - Опубликовано,
     *  то устанавливаем дату публикации на текущую
     *
     * @param BlogPost $blogPost
     */
    protected function setPublishedAt(BlogPost $blogPost)
    {
        $needSetPublishAt = empty($blogPost->published_at) && $blogPost->is_published;
    //    dd($needSetPublishAt);
        // Если published_at (когда опубликовано) пустой, а is_published (опубликовано) true (==1), то подставить дату в published_at
        if ($needSetPublishAt) {
            $blogPost->published_at = Carbon::now();
        }
    }

    /**
     * Если поле слаг пустое, то заполняем его конвертацией с заголовка
     *
     * @param BlogPost $blogPost
     */
    protected function setSlug(BlogPost $blogPost)
    {
    //    dd(__METHOD__, empty($blogPost->slug) );
        // Если слаг (будущий урл) пустой, то подставить туда  данные из title
        if (empty($blogPost->slug)) {
            $blogPost->slug = Str::slug($blogPost->title);
        }
    }

     /**
     * Установка значения поля content_HTML относительно поля content_raw
     *
     * @param BlogPost $blogPost
     */
    protected function setHTML(BlogPost $blogPost)
    {
        // Если поле content_raw изменено, то
        if ($blogPost->isDirty('content_raw')) {
            // Тут должна быть генерация markdown -> html
            $blogPost->content_html = $blogPost->content_raw;
        }
    }

    /**
     * Если не указан user_id, то установить пользователя по-умолчанию
     *
     * @param BlogPost $blogPost
     */
    protected function setUser(BlogPost $blogPost)
    {
        // Если пользователь авторизован и у него есть id, присвоить id свойству user_id,
        // иначе присвоить пользователя по умолчанию
        $blogPost->user_id = auth()->id() ?? BlogPost::UNKNOWN_USER;
    }





    /**
     * Нижеследующие методы наблюдают за событиями "после" их совершения.
     */

    /**
     * Handle the models blog post "created" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function created(BlogPost $modelsBlogPost)
    {
        //
    }

    /**
     * Handle the models blog post "updated" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function updated(BlogPost $modelsBlogPost)
    {
        //
    }

    /**
     * После вызова PostController@destroy попадаем сюда
     *
     * @param  \App\Models\BlogPost  $blogPost
     *
     * @return void
     */
    public function deleting(BlogPost $blogPost)
    {
        //dd(__METHOD__, $blogPost);

        // return false;  // удаление не произойдёт
    }

    /**
     * После вызова PostController@destroy и удаления записи попадаем сюда
     * Handle the models blog post "deleted" event.
     *
     * @param  \App\Models\BlogPost  $blogPost
     * @return void
     */
    public function deleted(BlogPost $blogPost)
    {
       // dd(__METHOD__, $blogPost);
    }

    /**
     * Handle the models blog post "restored" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function restored(BlogPost $modelsBlogPost)
    {
        //
    }

    /**
     * Handle the models blog post "force deleted" event.
     *
     * @param  \App\Models\BlogPost  $modelsBlogPost
     * @return void
     */
    public function forceDeleted(BlogPost $modelsBlogPost)
    {
        //
    }
}
