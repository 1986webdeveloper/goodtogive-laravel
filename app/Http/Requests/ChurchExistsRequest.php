<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class ChurchExistsRequest extends FormRequest
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
            'church_id'    => 'required|exists:project_donation_payments,church_id',
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
            'church_id.required' => 'Chruch is not define.',
            'start_date.required' => 'Start date not define.',
            'end_date.required' => 'End date not define.',
            'church_id.exists' => 'No donation found related church.'
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
