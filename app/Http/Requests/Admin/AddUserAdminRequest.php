<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AddUserAdminRequest extends FormRequest
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
            'firstname'    => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,NULL,NULL,is_deleted,0',
            'mobile' => 'required|unique:users,mobile',
            'password' => 'required|min:6',
            'cpassword' => 'required|same:password',
            'user_role_id' => 'required',
            'church_id' => 'required_unless:user_role_id,3',
            'image' => 'required',
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
            'firstname.required' => 'Please select firstname.',
            'lastname.required' => 'Please enter lastname.',
            'email.required' => 'Please enter email address.',
            'email.email' => 'Please enter valid email address.',
            'email.unique' => 'Email address already exists.',
            'mobile.required' => 'Please enter mobile number.',
            'mobile.unique' => 'Mobile number address already exists.',
            'password.required' => 'Please enter password.',
            'cpassword.required' => 'Please enter confirm password.',
            'cpassword.same' => 'Confirm password does not match with password.',
            'user_role_id.required' => 'Please select user role.',
            'image.required' => 'Please enter image.',
            'church_id.required_unless' => 'Please enter church name.'
        ];
    }
}
