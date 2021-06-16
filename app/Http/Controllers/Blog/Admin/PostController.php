<?php

namespace App\Http\Controllers\Blog\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\BlogPostRepository ;
use App\Http\Controllers\Blog\Admin\BaseController ;
use App\Repositories\BlogCategoryRepository ;


class PostController extends BaseController
{

     /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var blogCategoryRepository
     */
    private $blogCategoryRepository;

    /**
     *PostController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return view('blog.admin.posts.index', compact('paginator') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd(__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // show был исключён как маршрут в маршрутах в routes\web.php
    }

    /**
     * Show the form for editing the specified resource.
     * Показать страницу редактирования поста.
     * 
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd(__METHOD__, $id);

        $item = $this->blogPostRepository->getEdit($id);
        // Получить запись по id или null: app\Repositories\BlogPostRepository@getEdit
        
        // если null, то выдать ошибку 404
        if(empty($item)){
            abort(404);
        }

        // получить список категорий
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        // в агрументах: (путь до вьюхи, значения item и categoryList)
        return view('blog.admin.posts.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     * Сохранить (обновить) пост в БД.
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd(__METHOD__, $request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd(__METHOD__, $id);
        // существует "принудительный" вызов объекта $request класса Request
        // с помощью хелперской (внутренней) функции request() 
        // вместо вызова объекта $request->all() :
        //dd(__METHOD__, $id, request()->all() );
    }
}
