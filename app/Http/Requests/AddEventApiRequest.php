<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class AddEventApiRequest extends FormRequest
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
            'user_id'    => 'required|exists:users,id,user_role_id,3',
            'title'    => 'required',
            'description'   => 'required',
            'start_date'    => 'required',
            'end_date'    => 'required',
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
            'user_id.required' => 'Please select user.',
            'user_id.exists' => 'User not found.',
            'title.required' => 'Please enter title.',
            'description.required' => 'Please enter description.',
            'start_date.required' => 'Please enter start date.',
            'end_date.required' => 'Please enter end date.'
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
