<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Admin;

class UserController extends Controller
{
    public static function isAdmin($user) {
        return $user != null && !Admin::where('user_id','=',$user->id)->get()->isEmpty();
    }

    public static function getNotConfirmed() {
        return User::where('confirmed','=','0')->where('email_verified_at','!=','null')->get();
    }

    public static function setAdmin($user) {
        $user->confirmed = true;
        $user->save();
        $admin = new Admin();
        $admin->user_id = $user->id;
        $admin->save();
    }
}