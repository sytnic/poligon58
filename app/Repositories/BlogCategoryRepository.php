<?php

namespace App\Repositories;

use App\Models\BlogCategory as Model;
use Illuminate\Database\Eloquent\Collection;

/**
 * Наименование BlogCategoryRepository составлено из 
 *  имени модели BlogCategory и указания на Репозиторий
 */
class BlogCategoryRepository extends CoreRepository
{
    /** 
     * Реализация абстрактного метода из CoreRepository .
     * Model - это сокращение, здесь для BlogCategory .
     * @return string
     */
    protected function getModelClass()
    {       
        return Model::class;
    }


    /**
     * Получить модель для редактирования в админке.
     * Можно настроить получение только нужных полей. 
     * @param int $id
     *
     * @return model
     */
    public function getEdit(int $id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Получить список категорий для вывода в выпадающем списке.
     * Используется при 
     * "Добавить" CategoryController@create, 
     * редактировании категории CategoryController@edit
     * @return Collection
     * 
     */

    public function getForComboBox()
    {
        // уже используется репозиторий,
        // но тут берутся все поля из бд:
        // return $this->startConditions()->all();

        /* // берём определённые поля из бд */
        
        $columns = implode(',',[
            'id',
            ' CONCAT(id, ". ", title) AS id_title',
        ]);

        /* 
        //1 вариант. запрос, который выше
        $result[] = $this->startConditions()->all();
                     
        //2 вариант
        $result[] = $this
        ->startConditions()
        ->select('blog_categories.*',
            \DB::raw('CONCAT (id, ". ", title) AS id_title')) 
            // добавление еще одного столбца к запросу select
        ->toBase()
        // не агрегировать полученные данные в объекты класса BlogCategory
        // без toBase() будет получена (большая) коллекция BlogCategory
        ->get();
        // будет получена коллекция Std классов       
        */
        //3 вариант
        $result = $this
            ->startConditions()
            ->selectRaw($columns)
            // selectRaw позволяет подставлять сырые данные:
            // доп. столбцы в запросе, которых нет в таблице бд .
            ->toBase()
            ->get();
        
        // посмотреть, что выводят варианты с toBase() и без toBase()
        // можно применить массив $result[] ко всем 3-ем вариантам
        //dd($result);
        //dd($result->first());

        return $result;

    }

    /**
     * Получить все записи для вывода пагинатором
     *
     * @param int|null $perPage
     * по умолчанию null для вывода всех записей
     *
     * return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage = null)
    {
        // нужные нам поля
        $columns = ['id', 'title', 'parent_id'];
        
        $result = $this
            ->startConditions()
            ->select($columns)
            // /
            ->paginate($perPage);
    /*    
        // благодаря параметрам paginate()
        // ( про paginate: vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php )
        // возможна такая запись: 

        $result = $this
            ->startConditions()
            ->paginate($perPage, $columns);

        // проверка
        dd($result);       
     */      

        return $result;
    }

    
}