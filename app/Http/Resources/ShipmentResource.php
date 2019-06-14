<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ShipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'airway_bill_no' => $this->airway_bill_no,
            'amount_ordered' => $this->amount_ordered,
            'bar_code' => $this->bar_code,
            'booking_date' => $this->booking_date,
            'charges' => $this->charges,
            'client_address' => $this->client_address,
            'client_city' => $this->client_city,
            'client_email' => $this->client_email,
            'client_id' => $this->client_id,
            'client_name' => $this->client_name,
            'client_phone' => $this->client_phone,
            'cod_amount' => $this->cod_amount,
            'created_at' => $this->created_at,
            'derivery_date' => $this->derivery_date,
            'derivery_status' => $this->derivery_status,
            'dispatch_date' => $this->dispatch_date,
            'distance' => $this->distance,
            'driver' => $this->driver,
            'from_city' => $this->from_city,
            'id' => $this->id,
            'insuarance_status' => $this->insuarance_status,
            'lat' => $this->lat,
            'lat_to' => $this->lat_to,
            'lng' => $this->lng,
            'lng_to' => $this->lng_to,
            'location' => $this->location,
            'order_id' => $this->order_id,
            'paid' => $this->paid,
            'pickup_at' => $this->pickup_at,
            'pickup_id' => $this->pickup_id,
            'products' => $this->products,
            'receiver_name' => $this->receiver_name,
            'sender_address' => $this->sender_address,
            'sender_city' => $this->sender_city,
            'sender_email' => $this->sender_email,
            'sender_name' => $this->sender_name,
            'sender_phone' => $this->sender_phone,
            'speciial_instruction' => $this->speciial_instruction,
            'status' => $this->status,
            'statuses' => $this->statuses,
            'sub_total' => $this->sub_total,
            'to_city' => $this->to_city,
            'user_id' => $this->user_id,
            'weight' => $this->weight
        ];
    }
}
