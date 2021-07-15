<?php

namespace App\Http\Controllers\Blog\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Repositories\BlogPostRepository ;
use App\Http\Controllers\Blog\Admin\BaseController ;
use App\Repositories\BlogCategoryRepository ;
use App\Http\Requests\BlogPostUpdateRequest;
use Carbon\Carbon;
use Illuminate\Support\Str;


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
     * 
     * Заменили Request (по умолчанию) на свой BlogPostUpdateRequest
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        // dd(__METHOD__, $request->all(), $id);

        // Получение записи из БД
        $item = $this->blogPostRepository->getEdit($id);

        // Если запись в БД не найдена
        if(empty($item)){
            return back()
                ->withErrors(['msg'=>"Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        // Собираются входящие данные
        $data = $request->all(); 
        
        /* 
        // Логика проверок по идее должна быть вынесена отсюда из контроллера
        // Выносится в Обсервер

        // Если слаг (будущий урл) пустой, то подставить туда  данные из title
        if(empty($data['slug'])){
            $data['slug'] = Str::slug($data['title']);
        }
        // Если published_at (когда опубликовано) пустой, а is_published (опубликовано) true (==1), то подставить дату в published_at
        if(empty($item->published_at) && $data['is_published']){
            $data['published_at']= Carbon::now();
        }
        */

        // Данные обновляются
        $result = $item->update($data); 
        // задействуется модель BlogPost
        // и указанные в ней разрешенные поля - protected $fillable

        // Формирование ответа
        if($result){
            // Возврат на эту же страницу с Good сообщением
            return redirect()
                ->route('blog.admin.posts.edit', $item->id)
                ->with(['success'=> 'Успешно сохранено']);
        } else {
            // Bad way, возврат назад, сообщение об ошибке с input-полями.
            return back()
                ->withErrors(['msg'=>'Ошибка сохранения'])
                ->withInput();
        }

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
