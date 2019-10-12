<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Http\Controllers\GeneralController AS General;

class EditProjectApiRequest extends FormRequest
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
            'church_fund_id'    => 'required|exists:church_funds,id',
            'name'    => 'required',
            'description'    => 'required',
            'startdate'    => 'required',
            //'enddate'    => 'required',
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
            'church_fund_id.required' => 'Please select chruch fund.',
            'church_fund_id.exists' => 'Church fund is not found.',
            'name.required' => 'Please enter name.',
            'description.required' => 'Please enter description.',
            'startdate.required' => 'Please enter start date.',
            'enddate.required' => 'Please enter end date.'
        ];
    }
    
    public function failedValidation(Validator $validator)
    {
        $errorData =  $validator->errors();
        $message = 'Validation Error';
        return General::jsonResponse(2,$message,$errorData,'','',$type='api');
    }
}
