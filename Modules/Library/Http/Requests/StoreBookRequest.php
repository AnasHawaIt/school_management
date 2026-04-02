<?php

namespace Modules\Library\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */

    public function rules()
    {
        return [
            'title'       => 'required|string|max:255',
            'author_id'   => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'isbn'        => 'required|string|unique:books,isbn',
            'copies'      => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Book title is required',
            'author_id.exists' => 'Author not found',
            'category_id.exists' => 'Category not found',
            'isbn.unique' => 'ISBN already exists',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
