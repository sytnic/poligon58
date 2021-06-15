<?php

namespace App\Http\Controllers\Blog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

// Это базовый контроллер для блога
// и его подчиненных (внутренних) контроллеров.
// Он наследуется от общего Контроллера.
// Он не используется напрямую, от него только идёт наследование "вниз" (abstract)
// для подчиненных контроллеров.
abstract class BaseController extends Controller
{
    //
}
