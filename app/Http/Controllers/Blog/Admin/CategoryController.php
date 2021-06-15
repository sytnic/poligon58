<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Requests\BlogCategoryObjectRequest;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Blog\Admin\BaseController;
use App\Repositories\BlogCategoryRepository;

/**
 * Управление категориями блога
 *
 * @package App\Http\Controllers\BlogAdmin
 */
class CategoryController extends BaseController
{
    /**
     * @var BlogCategoryRepository
     * 
    */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct(); // construct в родительском BaseController

        // создание экземпляра класса (объекта)
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Срабатывает при переходе на admin/blog/categories
    public function index()
    {
    /* без репозитория, вызов всех полей из бд  
        //$d2 = BlogCategory::all();
        $paginator = BlogCategory::paginate(5);        
        //dd($d2, $paginator); //информация о переменных
    */

    /* для репозитория, вызов определенных полей из бд */
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);
 
        return view('blog.admin.categories.index', compact('paginator'));
    


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // Срабатывает при нажатии кнопки "Добавить"
    public function create()
    {
        // проверка c die
        //dd(__METHOD__);

        $item = new BlogCategory(); // создали пустой объект модели BlogCategory
        /* без использования репозитория: 
        // получим весь список со всеми полями от модели BlogCategory
        $categoryList = BlogCategory::all(); 
        */

        /* с использованием репозитория: */
        // получим список с определенными полями
        $categoryList
            = $this->blogCategoryRepository->getForComboBox();
        

        // вью
        // по сути, перечисление пути через точку в папке resources/views
        return view('blog.admin.categories.edit',
            compact('item','categoryList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // Срабатывает при нажатии кнопки "Сохранить" при добавлении новой категории,
    // поведение завиcит от @if ($item->exists) в edit.blade.php

    // Используем BlogCategoryCreateRequest ,
    // вместо Request по умолчанию
    
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input(); // данные, пришедшие с формы, если есть

        // если slug (Идентификатор) из формы пустой, то внутренней функцией str_slug
        // создаём красивый url из title.
        // Повторяемую логику в методах Контроллеров можно вынести в Обсервер.   
        if(empty($data['slug'])){
            $data['slug'] = str_slug($data['title']);
        }
        // посмотреть массив $data
        // dd($data);

        /*
        // Записать в БД. Способ 1.
        // Эта строчка вызовет в construct'e Model заполнение атрибутов,
        // назначенные в Модели BlogCategory в protected $fillable
        $item = new BlogCategory($data);
        // атрибуты можно перезаполнить вручную:
        $item->title = 'Тысяча восемьсот тридцать четыре';
        // проверка, +exists: false означает отсутствие в базе данных
        //dd($item);
        $item->save(); // save() записывает в БД и возвращает true или false
        */

        // Записать в БД. Способ 2.
        // Создаёт пустой объект BlogCategory, заполняет данными ($data), 
        // получаем объект $item, create в себе выполняет save() (записывает в БД)
        $item = (new BlogCategory())->create($data);
        // vendor\laravel\framework\src\Illuminate\Database\Eloquent\Builder.php

        // Можно if записать так:
        // if($item->exists)
        // if($item instanceof BlogCategory) .
        // Редиректы после сохранения .
        if($item) {
            return redirect()->route('blog.admin.categories.edit', [$item->id])
                ->with((['success' => 'Успешно сохранено']));
        } else {
            return back()->withErrors(['msg'=> 'Ошибка сохранения'])
                ->withInput(); // со старыми заполненными inputam'и
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
        // show не будет работать, 
        // если он не задан в routes/wep.php
        // проверка
        dd(__METHOD__);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * 
     * @return \Illuminate\Http\Response
     */
    // Срабатывает при клике по любой Категории
    public function edit($id, BlogCategoryRepository $categoryRepository)
    {
        /* при одиночном параметре id
        
        //$item[] = BlogCategory::find($id);  // return this element or null        
        //$item[] = BlogCategory::where('id', '=', $id)->first(); // return this element
        // dd(collect($item)->pluck('id'));  // покажет id для каждого item

        $item = BlogCategory::findOrFail($id); // return this element or 404       
        $categoryList = BlogCategory::all();

        */

        /* при параметрах id и Репозитория
           edit($id, BlogCategoryRepository $categoryRepository)  

        // другие способы создания объекта класса репозитория
        // вместо указания в параметре:
        //$categoryRepository = new BlogCategoryRepository();        
        //$categoryRepository = app(BlogCategoryRepository::class); // Равнозначно указанию в параметрах функции

        // getEdit() и getForComboBox() (выпадающий список категорий)
        // выгружает только те поля из БД, к-рые необходимы сейчас
        $item = $categoryRepository->getEdit($id);
        if(empty($item)) {
            abort(404);
        }
        $categoryList = $categoryRepository->getForComboBox();
        
        */

        /* при одиночном параметре id и использовании Репозитория */

        $item = $this->blogCategoryRepository->getEdit($id);
        if(empty($item)) {
            abort(404);
        }

        $categoryList = $this->blogCategoryRepository->getForComboBox();
        
        return view('blog.admin.categories.edit',
            compact('item', 'categoryList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // Срабатывает при нажатии кнопки "Сохранить" при обновлении категории,
    // поведение завиcит от @if ($item->exists) в edit.blade.php

    // public function update(Request $request, $id)  
                // request и id приходит с формы редактирования.
                // $request - это объект Request'a, работает с входящими данными.
                // $id ($name и т.п.) без указания, чей id, обозначает отношение к текущему контроллеру,
                // т.е. к категории .
                // В итоге заменим на свой Класс вместо Request

       public function update(BlogCategoryObjectRequest $request, $id)           
    {

        // Вся валидация будет внутри BlogCategoryObjectRequest,
        // он сам будет валидировать и
        // редиректить обратно с помощью фонового validate(),
        // validate() настраивать не нужно .

        /* Ручная Валидация при использовании (Request $request ..)*/
        
        // правила формы
        // при использовании (BlogCategoryObjectRequest $request ..)
        // используются внутри App\Http\Requests\ ,
        // где BlogCategoryObjectRequest расширяет FormRequest
        
        /*$rules = [
            'title' => 'required|min:5|max:200',
            'slug' => 'min:5|max:200',
            'description' => 'string|max:500|min:3',
            'parent_id' => 'required|integer|exists:blog_categories,id'
        ];
        */

        // способ 1, обращение к контроллеру, в фоне породится объект класса Validator
        // $validatedData = $this->validate($request, $rules);
        
        // способ 2, обращение к Request'у, в фоне породится объект класса Validator
        //$validatedData = $request->validate($rules);
        // в случае ошибки validate() вызывает дефолтный withErrors()
        // \vendor\laravel\framework\src\Illuminate\Foundation\Validation\ValidatesRequests.php

        // способ 3, вручную порождаем объект класса Validator
        // в make() передается массив и правила
        // \vendor\laravel\framework\src\Illuminate\Validation\Validator.php
        /*
        $validator = \Validator::make($request->all(), $rules);
        $validatedData[] = $validator->passes();    // проверка пройдена? true/false
        $validatedData[] = $validator->validate();  // массив полученных данных или редирект обратно в случае ошибки
        $validatedData[] = $validator->valid();     // PATCH, токен и данные, прошедшие проверку 
        $validatedData[] = $validator->failed();    // данные, не прошедшие проверку 
        $validatedData[] = $validator->errors();    // список сообщений об ошибках
        $validatedData[] = $validator->fails();     // есть любая ошибка? true/false
        
        dd($validatedData); // массив
        */

        /* end of Manual Validation */


        // проверка
        //dd(__METHOD__, $request->all(), $id);       
        //$id = 102102102;

        $item = BlogCategory::find($id); // find вернёт элемент или null

        // можно подставить выше несуществующий $id = 102102102; 
        // и запустить для проверки получения ошибки
        // или вывести dd($item) для проверки получения null

        if(empty($item)){               // если empty, в т.ч. null,
            return back()               // back (из хелперов) редиректит на шаг назад (назад по url)
                ->withErrors(['msg' => "Запись id=[{$id}]не найдена"]) // сохраняет ошибку в сессию и выводит её
                ->withInput();          // возвращает на место уже заполненные данные с input полей,
                ;                       // это _old_input в дебагбаре, в Session
        }

        $data = $request->all();  // массив всех данных, полученных реквестом
                                  // можно использовать $request->input();
        
        // если slug (Идентификатор) из формы пустой, то внутренней функцией str_slug
        // создаём красивый url из title.
        // Повторяемую логику в методах Контроллеров можно вынести в Обсервер.     
        if(empty($data['slug'])){
            $data['slug'] = str_slug($data['title']);
        }
        
        $result = $item
            ->fill($data) // перезаписывает свойства объекта $item
            ->save();     // cохраняет свойства в БД, вернёт true/false
        // Равнозначно делать так:
        // $result = $item->update($data);
        // описан в Model

        // следует реакция на сохранение: goodway/badway
        if ($result){
            return redirect() // redirect из хелперов
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success'=> 'Успешно сохранено']);    // уходит в сессию
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"])  // уходит в сессию
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
        //
    }
}
