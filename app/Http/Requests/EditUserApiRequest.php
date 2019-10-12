<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class EditUserApiRequest extends FormRequest
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
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->id.',id,is_deleted,0',
            'mobile'   => 'required|unique:users,mobile,'.$this->id.',id,is_deleted,0',
            'user_role_id' => 'required',
            //'mobile' => 'required_if:user_role_id,2|unique:users,mobile,2,user_role_id',            
            'church_id' => 'required|exists:users,id'
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
