<?php

namespace App\Http\Controllers;

use App\Chatty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Pusher\Laravel\Facades\Pusher;
use App\Notifications\ChattyNoty;
// use Illuminate\Notifications\Notification;
use Notification;
use App\Events\ChatMessage;

class ChattyController extends Controller
{
    /**
     *    * Create a new controller instance.
     *    *
     *    * @return void
     *    */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function getUserConvById(Request $request)
    {
        $user_id = $request->id;
        $auth_user = Auth::id();
        $chat = Chatty::whereIn('sender_id', [$auth_user, $user_id])
            ->whereIn('receiver_id', [$auth_user, $user_id])
            ->orderBy('created_at', 'ASC')->take(9)->latest()->get();
        return $chat;
    }

    public function saveUserChat(Request $request, $id)
    {
        // return $request->all();
        $sender_id = Auth::id();
        $receiver_id = $id;
        $chat = $request->chat;
        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'chat' => $chat,
            'read' => 1
        ];
        $chat = Chatty::create($data);
        $finalData = Chatty::where('id', $chat->id)->first();
        // Pusher::trigger('chat_channel', 'chat_save', ['message' => $finalData]);
        $user = User::find($id);
        $Chatty = 'Chatty';
		// Notification::send($users, new ShipmentNoty($finalData, $Chatty));
        Notification::send($user, new ChattyNoty($finalData, $Chatty));
        return $finalData;
    }
    /**
     * Send chat message
     * @param $request
     * @return void
     */
    public function sendMessage(Request $request)
    {
        $message = [
            "id" => $request->userid,
            "sourceuserid" => Auth::user()->id,
            "name" => Auth::user()->name,
            "message" => $request->message
        ];
        event(new ChatMessage($message));
        return "true";
    }
    public function chatPage()
    {
        $users = User::take(10)->get();
        return view('chat', ['users' => $users]);
    }
}
