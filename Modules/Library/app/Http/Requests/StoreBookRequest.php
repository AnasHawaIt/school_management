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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'publisher_id'=> 'required|exists:publishers,id',
            'author_id'   => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'isbn'        => 'required|string|unique:books,isbn',
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
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }
}
