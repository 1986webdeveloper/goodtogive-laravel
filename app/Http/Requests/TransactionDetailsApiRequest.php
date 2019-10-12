<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class TransactionDetailsApiRequest extends FormRequest
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
            'user_id' => 'required_without_all:church_id|exists:users,id',
		    'project_id' => 'required|exists:projects,id',
		    'church_id' => 'required_without_all:user_id|exists:users,id,user_role_id,3',
        ];
    }
    public function messages()
    {
        return [
            'user_id.required' => 'Please select user.',
            'user_id.exists' => 'User not found.',
            'project_id.required' => 'Please select project.',
            'project_id.exists' => 'Project not found.',
            'church_id.required' => 'Please select church.',
            'church_id.exists' => 'Church not found.',
            'required_without_all' => 'Please select user or church.',
            // '' => 'Please select user or church.',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
