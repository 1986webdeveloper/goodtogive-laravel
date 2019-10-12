<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class UserAddMultiChurchApiRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'church_id' => 'required|exists:users,id',
            'new_request' => 'required',
		];
    }
    public function messages()
    {
        return [
            'user_id.required' => 'Please enter user id.',
            'user_id.exists' => 'User not found.',
            'church_id.required' => 'Please enter church id.',
            'church_id.exists' => 'Church not found.',
            'new_request.required' => 'Please enter requests type.',
            ];
    }
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
