@extends('layouts.app')

@section('content')

    <div class="container">
{{--        @if(session('success'))                     --}}
{{--            <div class="row justify-content-center">--}}
{{--                <div class="col-md-12">             --}}
{{--                                                    --}}
{{--                </div>                              --}}
{{--            </div>                                  --}}
{{--        @endif                                      --}}
        <div class="row justify-content-center">
            <div class="col-md-12">
                <nav class="navbar navbar-toggleable-md navbar-light bg-faded">
                    <a class="btn btn-primary" href="{{ route('blog.admin.posts.create') }}">Написать</a>
                </nav>
                <div class="card">
                    <div class="card-body">

                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Автор</th>
                                <th>Категория</th>
                                <th>Заголовок</th>
                                <th>Дата публикации</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($paginator as $post)                       {{-- $post это объект класса BlogPost --}}
                                @php
                                    /** @var \App\Models\BlogPost $post           -- это для подсказок в PHPstorm --   */
                                @endphp
                                <tr @if(!$post->is_published) style = "background-color: #ccc; "@endif>
                                                      {{-- неопубликованная запись будет серой --}}
                                                                        
                                    {{-- Без отношений Eloquent : 
                                    <td>{{ $post->id }}</td>
                                    <td>{{ $post->user_id }}</td>
                                    <td>{{ $post->category_id }}</td>
                                    --}}

                                    {{-- Для отношений Eloquent : --}}
                                    <td>{{ $post->id }}</td>                                                                                         
                                    <td>{{ $post->user->name }} </td>           
                                    <td>{{ $post->category->title }}</td> 
                                    

                                    {{-- Без отношений Eloquent мы получаем только _id из одной таблицы,
                                         с отношениями Eloquent мы получаем реляционно связанные данные из других таблиц. 
                                    
                                    Динамические свойства Eloquent позволяют получить доступ к методам отношений,
                                    как если бы они были свойствами, определенными в модели:
                                      user     в $post->user->name      - используется как свойство, является методом $post'а,
                                          name принадлежит User'у как его свойство .
                                      category в $post->category->title - используется как свойство, является методом $post'а,
                                          title принадлежит category как его свойство .
                                      $post является объектом класса BlogPost (модели).
                                    --}}
                                    <td>
                                        <a href="{{ route('blog.admin.posts.edit', $post->id )}}">{{ $post->title }}</a>
                                    </td>
                                    <td>{{$post->published_at ? \Carbon\Carbon::parse($post->published_at)->format('d.M H:i') : '' }}</td>

                                        {{-- создаётся объект класса Carbon на основе $post->published_at и вызывается метод Carbon'a format  --}}
                                </tr>
                            @endforeach
                            
                            </tbody>
                            <tfoot></tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
        {{-- если общее число записей больше, чем суммировано подсчитано на этой странице, то выводим ссылки пагинации  --}}

        @if($paginator->total() > $paginator->count())
            <br>
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            {{ $paginator->links() }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection