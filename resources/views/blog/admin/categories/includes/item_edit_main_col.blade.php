@php
    /** @var \App\Models\BlogCategory $item */
    /** @var \Illuminate\Support\Collection $categoryList */
@endphp
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="card-title"></div>
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#maindata" role="tab">Основные данные</a>
                    </li>
                </ul>
                <br>
                <div class="tab-content">
                        <div class="tab-pane active" id="maindata" role="tabpanel">
                            <div class="group">
                                <label for="title">Заголовок</label>
                                <input name="title" value="{{ $item->title }}"
                                    id="title"
                                    type="text"
                                    class="form-control"
                                    minlength="3"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="slug">Идентификатор</label>
                                <input name="slug" value="{{ $item->slug }}"
                                       id="slug"
                                       type="text"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="parent_id">Родитель</label>
                                <select name="parent_id"
                                       id="parent_id"
                                       class="form-control"
                                       placeholder="Выберите категорию"
                                       required>
                                    @foreach($categoryList as $categoryOption)
                                        <option value="{{ $categoryOption->id }}"
                                            @if($categoryOption->id == $item->parent_id) selected @endif >                                            
                                            {{ $categoryOption->id_title }}
                                            {{--  для работы с репозиторием --}}
                                            
                                            {{-- $categoryOption->id }}. {{ $categoryOption->title --}}
                                            {{--  для работы без репозитория --}}

                                            {{-- Описание: --}}
                                            {{-- $categoryList берётся из CategoryController --}}
                                            {{-- @if подставляет родительскую категорию в форму --}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Описание</label>
                                <textarea   name="description" 
                                            id="description" 
                                            class="form-control" 
                                            rows="3">{{old('description', $item->description) }}
                                </textarea>
                                            {{-- можно использовать просто $item->description --}}
                                            {{-- old('description') вернёт уже заполненные данные обратно, 
                                            если форма будет возвращена с ошибкой;
                                            работает в связке с return back()->withInput() в соответствующем контроллере,
                                            иначе 
                                            выведет данные из БД с помощью $item->description .
                                            old() определён в хелперах . --}}
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>