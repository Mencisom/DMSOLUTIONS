<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController
{
    public function login(Request $request)
    {
        $user = DB::table('users')->where('user_email',$request->email)
            ->where('user_password',$request->password)
            ->first();
        if ($user != null) return view('login', ['user' => $user->user_first_name]);
        else{
            $user="";
            return view('login', ['user' => $user]);
        }
    }
}
