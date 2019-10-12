<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AddChurchAdminRequest extends FormRequest
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
            'email'=>  'required|email|unique:users,email,NULL,id,is_deleted,0',
            'mobile'   => 'required|unique:users,mobile,NULL,id,is_deleted,0',
            'password' => 'required|min:6',
            'cpassword' => 'required|same:password',
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
            'email.required' => 'Please enter email address.',
            'email.email' => 'Please enter valid email address.',
            'email.unique' => 'Email address already exists.',
            'mobile.required' => 'Please enter mobile number.',
            'mobile.unique' => 'Mobile number address already exists.',
            'password.required' => 'Please enter password.',
            'cpassword.required' => 'Please enter confirm password.',
            'cpassword.same' => 'Confirm password does not match with password.'
        ];
    }
}
