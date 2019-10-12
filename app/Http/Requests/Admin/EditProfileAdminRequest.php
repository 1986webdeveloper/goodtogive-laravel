<?php

namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Hash;


class EditProfileAdminRequest extends FormRequest
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
            'email' => 'required|unique:users,id,'.$this->id,
            'mobile' => 'required|unique:users,id,'.$this->id,
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
            'firstname.required' => 'Please enter first name.',
            'lastname.required' => 'Please enter last name.',
            'email.required' => 'Please enter email address.',
            'email.unique' => 'Email address is already exists.',
            'mobile.required' => 'Please enter mobile number.',
            'mobile.unique' => 'Mobile number is already exists.'
        ];
    }
    
}
