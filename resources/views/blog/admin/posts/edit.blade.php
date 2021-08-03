@extends('layouts.app')

@section('content')
    @php /** $var \App\Models\BlogPost $item */ @endphp {{-- Блок для подсказок в PhpStorm --}}

    <div class="container">

        @include('blog.admin.posts.includes.result_messages') {{-- Результат выдачи --}}

        {{-- Если запись по id существует, то action формы update; если не существует, то action - store. --}}
        {{-- blog.admin.posts - это маршрут, задан в routes\web.php; update и store - это методы  --}}
        @if ($item->exists)
            <form method="POST" action="{{ route('blog.admin.posts.update', $item->id) }}">
                @method('PATCH')
                {{-- PUT и PATCH используются для обновления записи. PUT - полное обновление, PATCH - частичное.
                     POST используется для создания записи.
                --}}
        @else
            <form method="POST" action="{{ route('blog.admin.posts.store') }}">
        @endif
        {{-- Под update и store лучше создавать отдельные вьюхи, а не одну, как здесь.   --}}

                        @csrf  {{-- Защита от атак. --}}
                        
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                @include('blog.admin.posts.includes.item_edit_main_col')
                            </div>
                            <div class="col-md-3">
                                @include('blog.admin.posts.includes.item_edit_add_col')
                            </div>
                        </div>
            </form>

            {{-- Дополнительная форма "Удалить". --}}
        @if($item->exists)
            <br>
                <form method="POST" action="{{route('blog.admin.posts.destroy', $item->id )}}">
                @method('DELETE') {{-- Метод Ларавеля для удаления вместо формального POST  --}}
                @csrf
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card card-block">
                                <div class="card-body ml-auto">
                                    <button type="submit" class="btn btn-link">Удалить</button>
                                </div>
                           </div>
                        </div>
                        <div class="col-md-3"></div>
                    </div>
                </form>
        @endif
    </div>
@endsection