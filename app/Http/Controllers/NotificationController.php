<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Shipment;
use Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function notifications()
    {
        $user = Auth::user();
        // return $user->notifications;
        $notification_data = [];
        // select * from `notifications` where `notifications`.`notifiable_id` = '1' and `notifications`.`notifiable_id` is not null and `notifications`.`notifiable_type` = 'App\User' and `read_at` is null order by `created_at` desc
        //  $unreadNotifications = DB::table('notifications')
        //                             ->where('notifiable_id', Auth::id())
        //                         ->whereNotNull('notifiable_id')
        //                         ->where('type', 'App\Notifications\ShipmentNoty')
        //                         ->where('notifiable_type', 'App\User')
        //                         ->whereNull('read_at')
        //                         ->get();
        foreach ($user->unreadNotifications as $notification) {
            // return $notification;
            // if ($notification->data['shipment']) {
            $notification_data[] = $notification->data['shipment'];
            // }
        }
        return $notification_data;
    }
    public function Chattynoty()
    {
        // $user = Auth::user();
        // return $user->notifications;
        $notification_data = [];
        // select * from `notifications` where `notifications`.`notifiable_id` = '1' and `notifications`.`notifiable_id` is not null and `notifications`.`notifiable_type` = 'App\User' and `read_at` is null order by `created_at` desc
        $unreadNotifications = DB::table('notifications')
            ->where('notifiable_id', Auth::id())
            ->whereNotNull('notifiable_id')
            ->where('type', 'App\Notifications\ChattyNoty')
            ->where('notifiable_type', 'App\User')
            ->whereNull('read_at')
            ->get();
        foreach ($unreadNotifications as $notification) {
            // return $notification->chatty;
            // if ($notification->data['shipment']) {
            $notification_data[] = $notification->data;
            // }
        }
        return $notification_data;
    }


    public function Notyshpments(Request $request, $id)
    {
        return Shipment::find($id);
    }

    public function read()
    {
        $user = Auth::user();
        foreach ($user->unreadNotifications as $notification) {
            $notification->markAsRead();
        }
    }
}
