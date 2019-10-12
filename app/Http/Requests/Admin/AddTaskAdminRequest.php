<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AddTaskAdminRequest extends FormRequest
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
            'user_id'    => 'required',
            'title' => 'required',
            'date' => 'required|date',
            'description' => 'required',
            'priority_id' => 'required',
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
            'title.required' => 'Please enter title.',
            'date.required' => 'Please enter date.',
            'date.date' => 'Please use date format.',
            'description.reuired' => 'Please enter description',
            'priority_id' => 'Please enter task priority'
        ];
    }
}
