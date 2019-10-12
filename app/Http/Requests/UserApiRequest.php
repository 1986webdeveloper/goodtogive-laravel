<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserApiRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|min:6',
            'user_role_id' => 'required',
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
            'password.required' => 'Please enter password.',
            'password.min' => 'Password should be contain atleast 6 digit.',
            'user_role_id.required' => 'Please enter user role.'
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        // print_r($errorData->messages);exit;
        // throw new HttpResponseException(response()->json([
        //     "meta" => [
        //         "status" => 2,
        //         "message" => $message,
        //     ],
        //     "data" => $errorData
        //         ], 200));
            return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
