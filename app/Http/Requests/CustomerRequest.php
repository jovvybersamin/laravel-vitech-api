<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'name' => 'required',
            'email' => $this->emailValidation(),
        ];
    }

    public function emailValidation() {
        if($this->getMethod() === 'PUT') {
            return "required|email|unique:customers,email,{$this->customer->id}";
        }
        return 'required|email|unique:customers';
    }

    /**
     * Get the error messages that apply to the request parameters.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name field is required',
            'email.required' => 'Email field is required',
            'email.email' => 'Email field must be a valid email.',
            'email.unique' => 'Email already exist.'
        ];
    }

}
