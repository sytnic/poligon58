<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogPostUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return false;
        return true; // меняем на true, чтобы автоматически срабатывала авторизация без авторизации
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3|max:200',
            'slug' => 'max:200',
            'excerpt' =>'max:500',
            'content_raw' =>'required|string|min:5|max:10000',
            'category_id' =>'required|integer|exists:blog_categories,id' 
              // exists означает: 
              // пришедшее значение category_id должно быть в таблице blog_categories в поле id
        ];
    }
}
