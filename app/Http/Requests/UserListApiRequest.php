<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class UserListApiRequest extends FormRequest
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

            'church_id'    => 'required|exists:users,id',
            'user_role_id'    => 'required'            
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
            'church_id.required' => 'Please select chruch.',
            'church_id.exists' => 'User not found.',
            'user_role_id.required' => 'Please select user role.',           
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
