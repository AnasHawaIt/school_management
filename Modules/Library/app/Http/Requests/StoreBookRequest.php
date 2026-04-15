<?php

namespace Modules\Library\app\Http\Requests;

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
            'description' => 'required|string',
            'photo'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'publisher_id'=> 'required|exists:publishers,id',
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
            'description.required' => 'Book description is required',
            'author_id.exists' => 'Author not found',
            'category_id.exists' => 'Category not found',
            'publisher_id.exists' => 'Publisher not found',
            'photo.image' => 'Photo is invalid',
            'isbn.unique' => 'ISBN already exists',
            'copies.required' => 'Copies is required',
            'copies.integer' => 'Copies is invalid',
            'copies.min' => 'Copies is invalid',
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
