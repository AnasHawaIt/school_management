<?php

namespace Modules\Academic\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCounselorRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'password'       => 'nullable|string|min:8',
            'gender'         => 'required|in:male,female',
            'specialization' => 'nullable|string|max:150',
            'notes'          => 'nullable|string',
            'avatar'          => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }
    public function messages(): array
    {
        return [

            'first_name.required' => 'الاسم الأول (بالإنجليزي) مطلوب',
            'first_name.max' => 'الاسم الأول لا يمكن أن يتجاوز 100 حرف',
            'last_name.required' => 'الكنية (بالإنجليزي) مطلوبة',
            'last_name.max' => 'الكنية لا يمكن أن تتجاوز 100 حرف',

            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'يجب إدخال بريد إلكتروني صحيح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً',

            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',


            'gender.required' => 'يرجى تحديد الجنس',
            'gender.in' => 'قيمة الجنس المدخلة غير صحيحة',

        ];
    }
}
