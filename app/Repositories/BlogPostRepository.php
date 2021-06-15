<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;

/**
 * Class BlogPostRepository.
 *
 * @package App\Repositories
 */
class BlogPostRepository extends CoreRepository
{

    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

     /**
     * Получить список статей для вывода в списке
     * (Админка)
     *
     * @return LengthAwarePaginator
     */

    public function getAllWithPaginate()
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',  // T/F
            'published_at',  // Null / или дата
            'user_id',       // используется в отношениях Eloquent
            'category_id'    // используется в отношениях Eloquent
        ];

        $result = $this->startConditions()  // создаётся новый экземпляр класса BlogPost
            ->select($columns)              // выбранные колонки
            ->orderBy('id', 'DESC')         // сортировка по убыванию по айди

            // используя отношения Eloquent и with() будут получены не просто _id из одной таблицы,
            // а реляционно связаннные данные из других таблиц .
            // Без with и с попыткой применения отношений Eloquent во вью (в .blade.php)
            // будет огромное количество запросов к БД с собиранием всех данных select * from .
            // with() будет работать и без применения отношений Eloquent во вью (в .blade.php) ,
            // но с применением будут отображаться данные из реляционно связанных таблиц.

        // Вариант 1, "жадная" загрузка (Lazy Load)
        //    ->with(['category', 'user'])   
                                    // загружаются отношения Eloquent category и user ,
                                    // определенные в модели BlogPost.
                                    // Захватывает больше, чем нужно:
                                    // select * from `blog_categories` where `blog_categories`.`id` in (1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11)
                                    // select * from `users` where `users`.`id` in (1, 2)
        // Вариант 2   
            ->with([ // Те же отношения Eloquent. Захватывает только то, что нужно:
                     // select `id`, `title` from `blog_categories` where `blog_categories`.`id` in (1, 2, 3, 5, 6, 8, 9, 10)
                     // select `id`, `name` from `users` where `users`.`id` in (1, 2)
                    // В таблице категорий по id ищет title,
                    // в таблице users по id ищет name:
                     // вариант 2.1
                    'category' => function ($query) {
                    $query->select(['id','title']);
                    },
                     // вариант 2.2
                    'user:id,name'
                  ])
            
            ->paginate(25);
        
        //    ->get();  // для проверки dd вместо paginate        
        // dd($result->first());

        return $result;
    }


}