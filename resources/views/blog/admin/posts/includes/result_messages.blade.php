{{-- Если есть какие-то ошибки, то выводить первую (или текст всех ошибок, в дальнейшем) --}}
@if($errors->any())
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ $errors->first() }}

                {{--
                @foreach($errors->all() as $errorText)
                    <li>{{$errorText}}</li>
                @endforeach
                --}}
            </div>
        </div>
    </div>
@endif

{{-- Если есть Сессионная переменная success, то получить и вывести её --}}
@if(session('success'))
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