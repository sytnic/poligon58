<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// чтобы не указывать отдельно get, post , delete.. для маршрутов, 
//   можно указать одной строкой:
//   указываются ресурс (uri, controller), имя маршрута names
//Route::resource('rest', 'RestTestController')->names('restTest');
// имя маршрута names('restTest'); можно не задавать

Auth::routes();
// регистрация новых маршрутов авторизации
Route::get('/home', 'HomeController@index')->name('home');
// маршрут в случае успешной авторизации

Route::group(['namespace'=>'Blog','prefix'=>'blog'], function(){
    Route::resource('posts', 'PostController')->names('blog.posts');
});
// для отображения индексного маршрута будет работать /blog/posts/ в адресной строке браузера
// Префикс и namespace можно было указать и так
/*
    Route::resource('blog/posts', 'Blog/PostController')->names('blog.posts');
*/

//Админка Блога
// url'om будет 'admin/blog/' + 'categories/' из блока Route::resource
// проверить можно будет командой php artisan route:list
// новая папка Blog\Admin создаётся при создании контроллера (12.)
$groupData = [
    'namespace' => 'Blog\Admin',
    'prefix' => 'admin/blog',
];
Route::group($groupData, function () {

    //BlogCategory
    // только эти методы будут задействованы, несмотря на 
    // создание ресурсом в контроллере всех методов по умолчанию (+ show, + destroy)
    $methods = ['index', 'edit', 'update', 'create', 'store'];
    Route::resource('categories', 'CategoryController')
        ->only($methods)
        ->names('blog.admin.categories');

     //BlogPosts
     // использовать все методы, кроме show
     Route:: resource('posts', 'PostController')
     ->except(['show'])
     ->names('blog.admin.posts');    
});