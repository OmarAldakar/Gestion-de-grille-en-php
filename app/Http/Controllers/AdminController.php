<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        return view('admin.index');
    }

    public function accept($id) {
        $user = User::find($id);
        if ($user == null) {
            return view ('admin.index');
        }
        if (request('btn-accept') != null) {
             $user -> confirmed = true;
             $user -> save();
        } else if (request('btn-decline') != null) {
            $user->delete();
        }
        return view ('admin.index');
    }
}
