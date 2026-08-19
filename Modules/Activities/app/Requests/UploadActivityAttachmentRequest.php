<?php


namespace Modules\Activities\app\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadActivityAttachmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:20480',

                'mimes:jpg,jpeg,png,webp,pdf,doc,docx,xls,xlsx,mp4',
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],
        ];
    }
}
