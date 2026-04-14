<?php

namespace Modules\Library\Http\Requests;

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
            'isbn' => 'sometimes|string|unique:books,isbn,' . $this->route('id'),
            'copies'      => 'sometimes|integer|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.string' => 'Title must be a string',
            'author_id.exists' => 'Author not found',
            'category_id.exists' => 'Category not found',
            'isbn.unique' => 'ISBN already exists',
        ];
    }
}
