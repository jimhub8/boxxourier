<?php namespace App\Handlers\Events;

use App\Call;
use App\Shipment;
// use App\Services\Email\Mailer; // This one I use to email as a service class
use Illuminate\Support\Facades\Auth;

class ShipmentEvents
{

    protected $user = null;

    public function __construct(Shipment $shipment)
    {
        // $this->user = Auth::user();
    }

    public function shipmentCreated(Shipment $shipment)
    {
        // dd($shipment['id']);
        // new Call;
        $call = new Call;
        $call->event = 'created';
        if (Auth::id()) {
            $call->user_id = Auth::id();
        } else {
            $call->user_id = auth('api')->user();
        }

        $call->shipment_id = $shipment->id;
        $call->shipment = serialize($shipment);
        $call->save();
        // dd($call);
        // Implement mailer or use laravel mailer directly
        // $this->mailer->notifyArticleCreated($article);
    }

    public function shipmentUpdated(Shipment $shipment)
    {
        // dd($shipment['id']);
        // dd($shipment);
        // new Call;
        $call = new Call;
        // $call->user_id = Auth::id();
        if (Auth::id()) {
            $call->user_id = Auth::id();
        } else {
            $call->user_id = auth('api')->user();
        }

        $call->event = 'updated';
        $call->shipment_id = $shipment['id'];
        $call->shipment = serialize($shipment);
        $call->save();
        // Implement mailer or use laravel mailer directly
        // $this->mailer->notifyArticleCreated($article);
    }

    public function shipmentDeleted(Shipment $shipment)
    {
        // dd($shipment['id']);
        // dd($shipment);
        // new Call;
        $call = new Call;
        $call->event = 'deleted';
        // $call->user_id = Auth::id();
        if (Auth::id()) {
            $call->user_id = Auth::id();
        } else {
            $call->user_id = auth('api')->user();
        }

        $call->shipment_id = $shipment['id'];
        $call->shipment = serialize($shipment);
        $call->save();
        // Implement mailer or use laravel mailer directly
        // $this->mailer->notifyArticleCreated($article);
    }

    // Other Handlers/Methods...
}
