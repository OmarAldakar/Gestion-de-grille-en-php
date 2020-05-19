<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\UE;

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
        } else if (request('btn-decline') != null && !$user->confirmed) {
               $user->delete();
        }
        return view ('admin.index');
    }

    public function createUEView() {
        return view('admin.create-ue');
    }
    public function createUE(Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'annee' => ['required', 'integer','max:5000','min:0']
        ]);
        $validator->validate();

        UE::create([
            'nom' => $data['name'],
            'annee' => $data['annee']
        ]);

        return view('admin.create-ue');
    }

    public function manage() {
        return view('admin.manage-users');
    }
}