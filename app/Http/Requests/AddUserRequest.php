<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class AddUserRequest extends FormRequest
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
            'email'=>  'required|email|unique:users,email,NULL,id,is_deleted,0',
            'firstname' => 'required',
            'lastname' => 'required',
            'password'=> 'required|min:6',
            'mobile'   => 'required|unique:users,mobile,NULL,id,is_deleted,0',
            'user_role_id' => 'required',
            'church_id' => 'required|exists:users,id',
            // 'referral_id' => 'required',
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
            'email.unique' => 'Email address already exists.',
            'email.email' => 'Please enter valid email address.',
            'password.required' => 'Please enter password.',
            'password.min' => 'Password should be at least 6 character',
            'firstname' => 'Please enter first name.',
            'lastname.required' => 'Please enter last name.',
            'mobile.required' => 'Please enter mobile number.',
            'mobile.unique' => 'Mobile number already exists.',
            'user_role_id.required' => 'User role is not found.',
            'church_id.required' => 'Please enter church',
            'church_id.exists' => 'Church not found'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
