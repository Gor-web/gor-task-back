<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function users() {
        $users = User::all();
        return $users;
    }

    public function show($id) {
        $user = User::find($id);
        return $user;
    }

    public function saveUser(Request $request){
        $id = $request->getUser_id;
        $user = User::find($id);
        if ($request->data['name'] != null) {
            $user->name = $request->data['name'];
        }
        if ($request->data['lastname'] != null) {
            $user->lastname = $request->data['lastname'];
        }

        $user->save();
    }



}
