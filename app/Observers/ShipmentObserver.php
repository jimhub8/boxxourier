<?php 
namespace App\Observers;
use Illuminate\Database\Eloquent\Model as Eloquent;
use App\Call;
use Illuminate\Support\Facades\Auth;

class ShipmentObserver {

    public function saving(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function saved(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function updating(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function updated(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function creating(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function created(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function deleting(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function deleted(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function restoring(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }

    public function restored(Call $call) {
        $call = new Call;
        $call->user_id = Auth::id();
        $call->shipment_id = $call->id;
        $call->save();
    }
}