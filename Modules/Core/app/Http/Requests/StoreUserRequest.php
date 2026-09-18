<?php

namespace Modules\Core\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',

            'first_name_ar' => 'nullable|string|max:100',
            'last_name_ar' => 'nullable|string|max:100',

            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // يفضل إضافة confirmed للتأكيد
            'phone' => 'nullable|string|max:20',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date|before:today',

            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // تعديل لنوع الملف وحجمه
            'user_type' => 'required|in:admin,teacher,student,parent,counselor',
            'is_active' => 'boolean',
            'role' => 'nullable|string|exists:roles,name',
        ];
    }

    public function messages(): array
    {
        return [

              'first_name.required' => 'الاسم الأول (بالإنجليزي) مطلوب',
              'first_name.max' => 'الاسم الأول لا يمكن أن يتجاوز 100 حرف',
              'last_name.required' => 'الكنية (بالإنجليزي) مطلوبة',
              'last_name.max' => 'الكنية لا يمكن أن تتجاوز 100 حرف',


              'first_name_ar.max' => 'الاسم الأول (بالعربي) لا يمكن أن يتجاوز 100 حرف',
              'last_name_ar.max' => 'الكنية (بالعربي) لا يمكن أن تتجاوز 100 حرف',

              'email.required' => 'البريد الإلكتروني مطلوب',
              'email.email' => 'يجب إدخال بريد إلكتروني صحيح',
              'email.unique' => 'هذا البريد الإلكتروني مستخدم مسبقاً',

              'password.required' => 'كلمة المرور مطلوبة',
              'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل',
              'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',

              'phone.max' => 'رقم الهاتف لا يمكن أن يتجاوز 20 حرفاً',
              'gender.required' => 'يرجى تحديد الجنس',
              'gender.in' => 'قيمة الجنس المدخلة غير صحيحة',
              'date_of_birth.date' => 'تاريخ الميلاد يجب أن يكون تاريخاً صحيحاً',

              'user_type.required' => 'نوع المستخدم مطلوب',
              'user_type.in' => 'نوع المستخدم المختار غير صحيح',
              'role.exists' => 'الصلاحية المختارة غير موجودة في النظام',
        ];
    }
}
