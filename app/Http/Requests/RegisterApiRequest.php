<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class RegisterApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email,NULL,NULL,is_deleted,0',
            'password' => 'required|min:6',
            'firstname' => 'required',
            'lastname' => 'required',
            'mobile'   => 'required|unique:users,mobile,NULL,NULL,is_deleted,0',
            'user_role_id' => 'required',
            // 'image' => 'mimes:jpeg,jpg,png,gif|required',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required' => 'Please enter email address.',
            'email.email' => 'Please enter valid email address.',
            'email.unique' => 'Email address already exists.',
            'password.required' => 'Please enter password.',
            'password.min' => 'Password should be contain atleast 6 digit.',
            'firstname.required' => 'Please enter firstname.',
            'lastname.required' => 'Please enter lastname.',
            'mobile.required' => 'Please enter mobile number.',
            'mobile.unique' => 'Mobile number already exists.',
            'user_role_id.required' => 'User roles is not define.'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
