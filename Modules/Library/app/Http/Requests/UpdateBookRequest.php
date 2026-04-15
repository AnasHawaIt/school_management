<?php

namespace Modules\Library\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'       => 'sometimes|string|max:255',
            'author_id'   => 'sometimes|exists:authors,id',
            'category_id' => 'sometimes|exists:categories,id',
            'isbn'        => 'sometimes|string|unique:books,isbn,' . $this->route('id'),
            'copies'      => 'sometimes|integer|min:0',
             'description' => 'sometimes|string',
             'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
             'publisher_id'=> 'sometimes|exists:publishers,id',
         ];
    }

    public function messages()
    {
        return [
            'title.string' => 'Title must be a string',
            'author_id.exists' => 'Author not found',
            'category_id.exists' => 'Category not found',
            'isbn.unique' => 'ISBN already exists',
            'copies.integer' => 'Copies must be an integer',
            'description.string' => 'Description must be a string',
            'photo.image' => 'Photo must be an image',
        ];
    }
}
