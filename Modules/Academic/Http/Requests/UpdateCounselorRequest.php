<?php

namespace Modules\Academic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCounselorRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'first_name'     => 'sometimes|string|max:100',
            'last_name'      => 'sometimes|string|max:100',
            'email'          => 'sometimes|email|unique:users,email',
            'password'       => 'nullable|string|min:8',
            'gender'         => 'sometimes|in:male,female',
            'specialization' => 'nullable|string|max:150',
            'notes'          => 'nullable|string',
            'avatar'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
    public function messages(): array
    {
        return [

            'first_name.max' => 'الاسم الأول لا يمكن أن يتجاوز 100 حرف',
            'last_name.max' => 'الكنية لا يمكن أن تتجاوز 100 حرف',

            'email.email' => 'يجب إدخال بريد إلكتروني صحيح',
            'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',


            'gender.in' => 'قيمة الجنس المدخلة غير صحيحة',

        ];
    }
}
