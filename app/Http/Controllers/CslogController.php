<?php

namespace App\Http\Controllers;

use App\Shipment;
use App\ShipmentStatus;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class CslogController extends Controller
{
    public function schedulepct(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $shipments = Shipment::select('id')->whereBetween('created_at', [$today, $tomorrow])->get();

        $ship_status = [];
        foreach ($shipments as $shipment) {
            $ship_status[] = $shipment->statuses;
        }
        return $ship_arr = array_flatten($ship_status);

        $users = User::setEagerLoads([])->get();
        $user_count = [];
        // if ($user->hasRole('Customer Service')) {
        foreach ($users as $key => $user) {
            $i = 0;
            $j = 0;
            if ($user->hasRole('Customer Service') && Auth::user()->country_id == $user->country_id) {
                foreach ($ship_arr as $ship_a) {
                    // return ($key);
                    if ($ship_a->user_id == $user->id) {
                        // return user->id;
                        // $user_count[] = Shipment::where('branch_id', user->id)->count();
                        $i++;
                        if ($ship_a->status == 'Scheduled') {
                            $j++;
                        }
                    }

                }
                $user_count[] = array(
                    'cou' => $j . '  ' . $user->name,
                    'name' => $user->name,
                    'id' => $key,
                    'count' => $i,
                );
            }
            // $ship_count = $ship_a->ship_a;
        }
        return $user_count;

        // $sh_count = Shipment::whereBetween('created_at', ['2019-01-29 00:00:00', '2019-01-30 00:00:00'])->count();
        // $id = [];

        // // return array_flatten($shipments);

        // foreach ($shipments as $shipment) {
        //     $id[] = $shipment->id;
        // }
        // // return ShipmentStatus::whereBetween('created_at', ['2019-01-29 00:00:00', '2019-01-30 00:00:00'])->whereIn('shipment_id', $id)->count();
        // $users = User::setEagerLoads([])->get();
        // $user_count = [];
        //     foreach ($users as $key => $user) {
        //     // return user->id;
        //     // $user_count[] = Shipment::where('branch_id', user->id)->count();
        //     if ($user->hasRole('Customer Service') && Auth::id() == $user->country_id) {
        //         $ship_count = ShipmentStatus::whereBetween('created_at', [$today, $tomorrow])->whereIn('shipment_id', $id)->where('user_id', $user->id)->groupBy('user_id')->count();
        //         $user_count[] = array(
        //             'cou' => ShipmentStatus::whereBetween('created_at', [$today, $tomorrow])->where('user_id', $user->id)->whereIn('shipment_id', $id)->groupBy('user_id')->count() . $user->name,
        //             'name' => $user->name,
        //             'id' => $key,
        //             'count' => (ShipmentStatus::whereIn('shipment_id', $id)->where('user_id', $user->id)->where('status', 'Scheduled')->groupBy('user_id')->count() / $ship_count) * 100,
        //             // 'calls' => (ShipmentStatus::whereIn('shipment_id', $id)->where('user_id', $user->id)->where('status', 'Scheduled')->count() / $sh_count) * 100,
        //         );
        //     }
        // }
    }

    public function allLogs(Request $request)
    {
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $shipments = Shipment::setEagerLoads([])->select('id')->whereBetween('created_at', [$today, $tomorrow])->get();
        // $sh_count = Shipment::whereBetween('created_at', ['2019-01-29 00:00:00', '2019-01-30 00:00:00'])->count();
        $id = [];

        // return array_flatten($shipments);

        foreach ($shipments as $shipment) {
            $id[] = $shipment->id;
        }
        // return ShipmentStatus::whereBetween('created_at', ['2019-01-29 00:00:00', '2019-01-30 00:00:00'])->whereIn('shipment_id', $id)->count();
        $users = User::setEagerLoads([])->get();
        $user_count = [];
        $all_statuses = ShipmentStatus::whereBetween('created_at', [$today, $tomorrow])->distinct('shipment_id')->count('shipment_id');
        foreach ($users as $key => $user) {
            // return user->id;
            // $user_count[] = Shipment::where('branch_id', user->id)->count();
            if (!$user->hasRole('Admin') && Auth::id() == $user->country_id) {
                $ship_count = ShipmentStatus::whereBetween('created_at', [$today, $tomorrow])->whereIn('shipment_id', $id)->where('user_id', $user->id)->groupBy('user_id')->count();
                $shipment_count = ShipmentStatus::whereIn('shipment_id', $id)->where('user_id', $user->id)->distinct('shipment_id')->count('shipment_id');
                $user_count[] = array(
                    'cou' => ShipmentStatus::whereBetween('created_at', [$today, $tomorrow])->where('user_id', $user->id)->groupBy('user_id')->count() . $user->name,
                    'name' => $user->name,
                    // 'id' => $key,
                    // 'count' => (ShipmentStatus::whereIn('shipment_id', $id)->where('user_id', $user->id)->where('status', 'Scheduled')->groupBy('user_id')->count()),
                    'count' => ($shipment_count > 0) ? ($shipment_count/$all_statuses)*100 : $shipment_count
                );
                // $retVal = 
            }
        }
        return $user_count;
    }
}
