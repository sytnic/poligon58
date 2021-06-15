@extends('layouts.app')

@section('content')
    @php /** $var \App\Models\BlogCategory $item */ @endphp


    {{--    в зависимости от целей 
            (форма редактирования (под update) или форма создания новой категории (под store)) 
            верхушка кода у формы меняется --}} 

    {{-- $item подхватывается из методов CategoryController ,
         в случае store он с пустыми атрибутами, 
         создан как пустой объект в контроллере
    --}}

    @if ($item->exists)
        <form method = "POST" action = "{{ route('blog.admin.categories.update', $item->id) }}" >
            @method('PATCH')
    @else
        <form method = "POST" action = "{{ route('blog.admin.categories.store') }}">
    @endif    
            {{--    PATCH - это метод отправки формы, 
                    в таблице routes задаётся PUT или PATCH,
                    использование POST может выдать 404 ошибку --}}         
        
        @csrf       {{--    защита формы от атак    --}}        

        <div class="container">
            {{--  отображение в случае ошибки сохранения формы в БД.
                  Если в переменной errors есть хоть что-то, то...
            --}}

            @php
                /** @var \Illuminate\Support\ViewErrorBag $errors */
            @endphp
            @if($errors->any())
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ $errors->first() }}                            
                        </div>
                    </div>
                </div>
            @endif

            @if(session('success'))
            {{-- session() - это хелперская функция, 
                 ищет ключ и получает значение по нему ниже,
                 ключ и значение заданы в массиве в контроллере.
             --}}
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <div class="alert alert-success" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            {{ session()->get('success') }}
                        </div>
                    </div>
                </div>
            @endif

            {{--  отображение в случае отсутствия ошибок формы --}}

            <div class="row justify-content-center">
                <div class="col-md-8">
                   @include('blog.admin.categories.includes.item_edit_main_col')   
                </div>
                <div class="col-md-3">
                   @include('blog.admin.categories.includes.item_edit_add_col')  
                </div>
            </div>
        </div>
    </form>
@endsection