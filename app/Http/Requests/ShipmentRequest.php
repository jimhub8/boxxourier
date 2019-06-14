<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ShipmentRequest extends FormRequest
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
            'client_name' => 'required',
            'client_phone' => 'required|numeric',
            'client_email' => 'required|email',
            'client_address' => 'required',
            'client_city' => 'required',
            'assign_staff' => 'required',
            'airway_bill_no' => 'required',
            'shipment_type' => 'required',
            'payment' => 'required',
            'total_freight' => 'required',
            'insuarance_status' => 'required',
            'booking_date' => 'required',
            'derivery_date' => 'required|date',
            'derivery_time' => 'required',
            'bar_code' => 'required',
            'customer_id' => 'required|numeric',
            'sender_name' => 'required',
            'sender_email' => 'required|email',
            'sender_phone' => 'required|numeric',
            'sender_address' => 'required',
            'sender_city' => 'required',
            'user_id' => 'required|numeric',
            'shipment_id' => 'required|numeric',
            'company_id' => 'required|numeric',
        ];
    }
}
