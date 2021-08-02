<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;

    /**
     * ID корня
     */
    const ROOT = 1;

    // $fillable разрешает методу fill() этого объекта перезаписывать в объекте 
    // только перечисленные атрибуты в формах, в инпутах
    protected $fillable
    = [
        'title',
        'slug',
        'parent_id',
        'description',
    ];

    

    /**
     * Получить родительскую категорию 
     * 
     * @return BlogCategory
     * 
     * Может отрабатывать как свойство в $item->parentCategory 
     * в resources\views\blog\admin\categories\index.blade.php
     */
    public function parentCategory()
    {
        // текущая запись принадлежит записи из этой же категории
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }


    /**
     * Пример аксессора (Accessor)
     *
     * Должен начинаться на get и заканчиваться на Attribute,
     * между которыми даётся название будущему свойству(!) для обращения к нему,
     * например, во вью - $item->parentTitle или $item->parent_title .
     *  
     * @url https://laravel.com/docs/5.8/eloquent-mutators
     *
     * @return string
     */
    public function getParentTitleAttribute()
    {
        // Если title есть, то возвращаем его,
        // иначе следует логика, если текущий объект является корневым
        $title = $this->parentCategory->title
            ?? ($this->isRoot()
            ? 'Корень'
            : '???');

        return $title;
    }

    /**
     * Является ли текущий объект корневым
     *
     * @return bool
     */
    public function isRoot()
    {
     return $this->id === BlogCategory::ROOT;
    }


    public function attributes() {
        // 
    }

    /**
     * Пример аксессора (Accessor)
     * срабатывает до перезаписи $item->title в \Blog\Admin\CategoryController.php,
     * использует значение из БД
     * @param string $valueFromDB
     *  
     */
    public function getTitleAttribute ($valueFromObject) {

        return mb_strtoupper($valueFromObject);
    }

    /**
     * Пример мутатора
     * срабатывает после перезаписи $item->title в \Blog\Admin\CategoryController.php,
     * использует перезаписанное значение 
     * @param string $incomingValue
     * 
     */
    public function setTitleAttribute($incomingValue) {
        $this->attributes['title'] = mb_strtolower($incomingValue);
    }

}
