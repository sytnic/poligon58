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
use App\Models\BlogPost;
use App\Http\Requests\BlogPostCreateRequest;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;


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
        $item = new BlogPost(); // создали пустой объект модели BlogPost
        // используется во вью

        /* с использованием репозитория: */
        // получим список с определенными полями
        $categoryList
            = $this->blogCategoryRepository->getForComboBox();

        // вью
        // по сути, перечисление пути через точку в папке resources/views
        return view('blog.admin.posts.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BlogPostCreateRequest  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        $item = (new BlogPost())->create($data);

        if ($item) {
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);
            // dispatch() выполнит задачу сразу или
            // поставит в очередь если у класса (джоба) BlogPostAfterCreateJob есть implements ShouldQueue
            // В таблице jobs будет создан объект Json, его некоторые параметры (https://jsoneditoronline.org):
            // maxTries - количество попыток
            // delay - отсрочка выполнения
            // timeout - разрешенное время процесса выполнения задачи
            // timeoutAt - дата, до который будут попытки

            return redirect()->route('blog.admin.posts.edit', [$item->id])
                             ->with((['success' => 'Успешно сохранено']));
        } else {
            return back()->withErrors(['msg'=> 'Ошибка сохранения'])
                         ->withInput();
        }
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
        // dd(__METHOD__, $id);
        // существует "принудительный" вызов объекта $request класса Request
        // с помощью хелперской (внутренней) функции request() 
        // вместо вызова объекта $request->all() :
        //dd(__METHOD__, $id, request()->all() );

        //софт-удаление, в бд остается
        $result = BlogPost::destroy($id);

        //полное удаление из бд
        //$result = BlogPost::find($id)->forceDelete();

        if($result){

            //BlogPostAfterDeleteJob::dispatch($id);
            // delay(20) - это отсрочка выполнения задачи на столько секунд
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            // Варианты запуска очереди задач,
            // используются методы трейта Dispatchable в классе BlogPostAfterDeleteJob

            // 1. Выполнится даже без интерфейса implements ShouldQueue класса BlogPostAfterDeleteJob
            //BlogPostAfterDeleteJob::dispatchNow($id);
            
            // 2. Хелперские функции
            //dispatch(new BlogPostAfterDeleteJob($id));
            //dispatch_now(new BlogPostAfterDeleteJob($id));

            // 3. Если в головном (расширяемом) классе (Controller) есть трейт use DispatchesJobs,
            // то можно вызывать $this из этого класса .
            //$this->dispatch(new BlogPostAfterDeleteJob($id));
            //$this->dispatchNow(new BlogPostAfterDeleteJob($id));

            return redirect()
                ->route('blog.admin.posts.index')
                ->with(['success' => "Запись id [$id] удалена"]);
        } else {
            return back()->withErrors(['msg'=>'Ошибка удаления']);
        }

    }
}
