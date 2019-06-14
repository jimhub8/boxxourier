<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\User;
use App\Shipment;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function getPermissions()
    {
        return Permission::all();
    }

    public function getUsersCount(Request $request)
    {
        if (empty($request->id)) {
            return User::where('country_id', Auth::user()->country_id)->count();
        } else {
            return User::where('country_id', $request->id)->count();
        }
    }

    public function getShipmentsCount(Request $request)
    {
        if (empty($request->id)) {
            return Shipment::where('country_id', Auth::user()->country_id)->count();
        } else {
            return Shipment::where('country_id', $request->id)->count();
        }
    }


    public function getDashCount(Request $request)
    {
        return Shipment::count();
    }

    public function getCanceledCount(Request $request)
    {
        if (empty($request->id)) {
            return Shipment::where('country_id', Auth::user()->country_id)->where('status', 'Cancelled')->count();
        } else {
            return Shipment::where('country_id', $request->id)->where('status', 'Cancelled')->count();
        }
    }
    
// Dashboard
    public function delayedShipmentCount(Request $request)
    {
        if (empty($request->id)) {
            return Shipment::where('status', 'Delayed')->count();
        } else {
            return Shipment::where('status', 'Delayed')->count();
        }
    }

    public function scheduledShipmentCount(Request $request)
    {
        if (empty($request->id)) {
            return Shipment::where('country_id', Auth::user()->country_id)->where('status', 'Scheduled')->count();
        } else {
            return Shipment::where('country_id', $request->id)->where('status', 'Scheduled')->count();
        }
    }

    public function deriveredShipmentCount(Request $request)
    {
        if (empty($request->id)) {
            return Shipment::where('country_id', Auth::user()->country_id)->where('status', 'Derivered')->count();
        } else {
            return Shipment::where('country_id', $request->id)->where('status', 'Derivered')->count();
        }
    }


    public function dispatchedShipmentCount(Request $request)
    {
        if (empty($request->id)) {
            return Shipment::where('country_id', Auth::user()->country_id)->where('status', 'Dispatched')->count();
        } else {
            return Shipment::where('country_id', $request->id)->where('status', 'Dispatched')->count();
        }
    }
} 