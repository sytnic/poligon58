<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogCategory extends Model
{
    use SoftDeletes;

    // $fillable разрешает методу fill() этого объекта перезаписывать в объекте 
    // только перечисленные атрибуты в формах, в инпутах

    protected $fillable
    = [
        'title',
        'slug',
        'parent_id',
        'description',
    ];

   
}
