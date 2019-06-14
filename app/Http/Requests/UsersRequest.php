<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
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
            'form.name' => 'required',
            'form.password' => 'required|min:6',
            'form.email' => 'required|email',
            'form.phone' => 'required|numeric',
            'form.branch_id' => 'required',
            'form.address' => 'required',
            'form.city' => 'required',
            'form.country' => 'required',
            'form.role_id' => 'required'
        ];
    }
}
