<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Blog\BaseController as GuestBaseController;

// Это базовый контроллер для админки блога
// и его внутренних контроллеров.
// Он наследуется от базового контроллера блога.
// Он не используется напрямую, от него только идёт наследование "вниз" (abstract)
// для подчиненных (внутренних) контроллеров.
abstract class BaseController extends GuestBaseController
{

    public function __construct() {
        //Инициализация общих параметров для админ-контроллеров

    }


}