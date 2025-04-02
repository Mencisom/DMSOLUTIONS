<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController
{
    public function index(){
        $users = DB::table('users_rol')->get();
        return view('administration', ['users' => $users]);
    }

    public function addUser(Request $request)
    {
        $user = new User();
        $user = DB::table('users')->where('user_email',$request->input('userEmail'))->get();
        $message = "";
        if($user -> isEmpty()){
            $user = new User();
            $user -> id = 0;
            $user -> user_first_name = $request->input('userNames');
            $user -> user_last_name = $request->input('userLastNames');
            $user -> user_email = $request->input('userEmail');;
            $user -> user_password = $request->input('userPassword');
            $user -> user_role = $request->input('menuRol');
            $user -> user_status = 1;

            try {
                $user -> save();

            }catch (Exception $e) {
                $message = $e->getMessage();
            }
        }
        else{
            $message = "Email already exists!";
            session()->flash('status', $message);
        }
        return to_route('administration');
    }

    public function consultRole(){
        $roles = DB::table('roles')->get();
        return $roles;
    }

    public function destroy(Request $request){
        $id = $request->id;
        $user = User::find($request->id);
        $user->delete();
        return to_route('administration');
    }

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

    public function consultUserDetail(Request $request){
        $user = DB::table('users')->where('id',$request->userDetail)->get()->first();
        return $user;
    }

    public function updateUser(Request $request, User $user){
        $user = User::find($request->hiddenUserId);
        $user -> user_first_name = $request->input('updateUserNames');
        $user -> user_last_name = $request->input('updateUserLastNames');
        $user -> user_email = $request->input('updateUserEmail');;
        $user -> user_password = $request->input('updateUserPassword');
        $user -> user_role = $request->input('menuActualizarRol');
        $user -> user_status = 1;

        try{
            $user -> save();
            return to_route('administration');
        }catch (Exception $e){
            $message = $e->getMessage();
            to_route('administration')->with('error', $message);
        }
    }

}
