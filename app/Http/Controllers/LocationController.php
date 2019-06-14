<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipment;

class LocationController extends Controller
{
    public function locationUpdate(Request $request, $id)
    {
        // return $request->all();
        $lat = [];
        $lng = [];
        foreach ($request->markers as $loc) {
            // return $loc;
            foreach ($loc as $location) {
                $lat[] = $location['lat'];
                $lng[] = $location['lng'];
                // $lat = $location['lat_to'];
                // $lng = $location['lng_to'];
            }
        }
        // return $lat[0];
        $shipment = Shipment::find($id);
        $shipment->lat = $lat[0];
        $shipment->lng = $lng[0];
        $shipment->lat_to = $lat[1];
        $shipment->lng_to = $lng[1];
        $shipment->distance = $request->dist;
        $shipment->save();
        return $shipment;
    }
	public function getcoordinatesArray($id) {
         $coodinates = Shipment::select('lat', 'lat_to', 'lng', 'lng_to')->find($id);
        //  $position = [];
        //  $num = "3.14";
        //  $float = (float)$num;
         $position = array('lat' => (float)$coodinates->lat , 'lng' => (float)$coodinates->lng); 
         $position_to = array('lat' => (float)$coodinates->lat_to , 'lng' => (float)$coodinates->lng_to);
         $merge_to = array('position' =>$position_to);
         $merge = array('position' => $position);
         $merge_arr = [];
        //  array_merge($merge, $merge_to);
         for ($i=0; $i < 1; $i++) { 
            $merge_arr[] = $merge_to;
            $merge_arr[] = $merge;
         }
         return $merge_arr;
		// $marker = $re
    }
    
    public function paid(Request $request, $id)
    {
        // return $request->all();
        $paid = Shipment::find($id);
        $paid->paid = $request->paid;
        $paid->save();
        return $paid;
    }
}
