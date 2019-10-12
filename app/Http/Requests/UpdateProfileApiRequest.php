<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class UpdateProfileApiRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email,'.$this->id.',id,is_deleted,0',
            'firstname' => 'required',
            'lastname' => 'required',
            'mobile'   => 'required|unique:users,mobile,'.$this->id.',id,is_deleted,0'
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
            'firstname.required' => 'Please enter firstname.',
            'lastname.required' => 'Please enter lastname.',
            'mobile.required' => 'Please enter mobile number.',
            'church_id.required' => 'Church not found.'
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
