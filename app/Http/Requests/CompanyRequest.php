<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CompanyRequest extends FormRequest
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
    public function rules(Request $request)
    {
        return [
            'location' => 'required',
            'company_name' => 'required|date',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'address' => 'required',
            'admin' => 'required',
            'user_id' => 'required|numeric',
        ];
    }
}
