<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContainerRequest extends FormRequest
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
            'bar_code' => 'required',
            'address' => 'required',
            'city' => 'required',
            'assign_staff' => 'required',
            'derivery_date' => 'required|date',
            'derivery_time' => 'required',
            'company_id' => 'required|numeric'
        ];
    }
}
