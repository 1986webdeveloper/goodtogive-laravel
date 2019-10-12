<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AddProjectAdminRequest extends FormRequest
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
            'church_fund_id' => 'required',
            'name' => 'required',
            'goal_amount' => 'required',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after:start_date',
            'description' => 'required',
            'project_image'=>'required',
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
            'church_fund_id.required' => 'Please select church fund.',
            'name.required' => 'Please enter name.',
            'goal_amount.required' => 'Please enter amount.',
            'startdate.required' => 'Please enter start date.',
            'startdate.date' => 'Please choose start date.',
            'enddate.required' => 'Please enter end date.',
            'enddate.date' => 'Please choose end date.',
            'enddate.after' => 'Please choose date next to the start date',
            'description.reuired' => 'Please enter description',
            'project_image.required' => 'Please select image'
        ];
    }
}
