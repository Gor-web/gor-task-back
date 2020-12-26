<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FriendsController extends Controller
{
    public function friends(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'my_id' => 'required',
            'friend_id' => 'required',
        ]);

        Friend::create([
            'my_id' => $request->my_id,
            'friend_id' => $request->friend_id,
        ]);
        return response('success', 200);
    }

    public function myRequests(Request $request)
    {
        $id = $request->id;
        $friends = Friend::where('friend_id', $id)->with('user')->get();
        return $friends;
    }

    public function accept(Request $request)
    {
        $friend_id = $request->id;
        $my_id = $request->my_id;
        $users = DB::table('requests')->where('my_id', $friend_id)
            ->where('friend_id', $my_id)->update([
                'accepted_id' => $friend_id,
                'my_id' => null
            ]);
        return $users;
    }

    public function decline(Request $request)
    {
        $friend_id = $request->id;
        $my_id = $request->my_id;
        $friends = DB::table('requests')->where('my_id', $friend_id)
            ->where('friend_id', $my_id)->delete();
        return $friends;
    }

    public function myFriends(Request $request)
    {
        $id = $request->id;
        $friendsRequests = Friend::where('friend_id', $id)->whereNotNull('accepted_id')->get()->pluck('friendInfo');
        $MyFriendsRequests = Friend::where('accepted_id', $id)->whereNotNull('friend_id')->get()->pluck('acceptedToMe');
        return [$friendsRequests, $MyFriendsRequests];
    }

    public function delete(Request $request)
    {
        $my_friend_id = $request->friend_id;
        $my_id = $request->my_id;
        $friendsRequests = Friend::where('accepted_id', $my_id)->where('friend_id', $my_friend_id)->delete();
        $myRequests = Friend::where('accepted_id', $my_friend_id)->where('friend_id', $my_id)->delete();


        return [$friendsRequests,$myRequests];

    }
}
