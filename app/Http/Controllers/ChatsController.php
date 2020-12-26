<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class ChatsController extends Controller
{

    public function message()
    {
        $users = User::get();
        return $users;
    }

    public function send(Request $request)
    {
        Chat::create([
            'my_id' => $request->my_id,
            'friend_id' => $request->getter_id,
            'message' => $request->message,

        ]);
        return $request;
    }

    public function my_message()
    {
        $id = auth()->user()->id;
        $senderChat = Chat::where('my_id', $id)->with('sender')->get();

        $getterChat = Chat::where('friend_id', $id)->with('getter')->get();
       return [$senderChat,$getterChat];
    }

    public function deleteMessage(Request $request) {
        $message = $request->message;
        $my_id = $request->my_id;
        $friendsRequests = Chat::where('my_id', $my_id)->where('message', $message)->delete();
        $myRequests = Chat::where('message', $message)->where('friend_id', $my_id)->delete();


        return $request;
    }
}
