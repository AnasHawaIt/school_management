<?php
 namespace Modules\Library\app\Http\Requests;

 use Illuminate\Foundation\Http\FormRequest;

 class LoginRequest extends FormRequest {
     public function authorize() {
         return true;
     }
     public function rules() {
         return [
            'username' => "required|exists:members,username",
             'password' => 'required|exists:members,password',
         ];
     }

     public function messages() {
         return [
             'username.exsts'=>'username not exists in members table',
             'password.exsts'=>'password not exists in members table',
         ];
     }
 }

