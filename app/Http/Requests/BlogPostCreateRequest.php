<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:5|max:200|unique:blog_posts',
            'slug' => 'max:200',
            'content_raw' =>'required|string|min:5|max:10000',
            'category_id' =>'required|integer|exists:blog_categories,id'
        ];
    }

    /**
     *  Для точечного перевода берутся значения из rules()
     */
    public function messages()
    {
        return [
            'title.required' => 'Введите заголовок статьи',
            'content_raw.min' => 'Минимальная длина статьи [:min] символов',
            // [:min] берётся из значения content_raw.min в rules() (min:5).
            // Английский оригинал перевода лежит на файле resources\lang\en\validation.php,
            // можно также создавать свой файл ru\validation.php вместо этого точечного перевода.
        ];
    }

    /**
     *  Для точечного перевода атрибутов (ключей) из rules()
     */
    public function attributes()
    {
        return [
            'title' => 'Заголовок',
        ];
    }



}
