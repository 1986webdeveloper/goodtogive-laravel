<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AddReferrarAdminRequest extends FormRequest
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
            'church_id'    => 'required',
            'referrer_Id' => 'required',
            'vendor_id' => 'required',
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
            'church_id.required' => 'Please select church.',
            'referrer_Id.required' => 'Please enter referrer id.',
            'vendor_id.required' => 'Please enter vendor id.',
            ];
    }
}
