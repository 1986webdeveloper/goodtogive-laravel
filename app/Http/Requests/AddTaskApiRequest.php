<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class AddTaskApiRequest extends FormRequest
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
            'user_id'    => 'required|exists:users,id',
            'title'    => 'required',
            'description'    => 'required',
            'date'    => 'required',
            'priority_id' => 'required',
            'group_id' => 'required_if:member_id,""',
            'member_id' => 'required_if:group_id,""',
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
            'date.required' => 'Please enter date.',
            'priority_id.required' => 'Please enter task priority.',
            'group_id.required_if' => 'Please select at least one group or individual user.',
            'member_id.required_if' => 'Please select at least one group or individual user.',
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
