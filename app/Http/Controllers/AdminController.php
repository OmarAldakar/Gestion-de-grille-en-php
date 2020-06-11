<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Admin;
use Illuminate\Support\Facades\Validator;
use App\UE;
use App\Repartition;
use App\Exercice;
use Illuminate\Support\Facades\Auth;

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

    public function deleteUser($id) {
        $user = User::find($id);
         
        // Si je supprime quelqu'un qui corrige des exercice je récupère ses corrections
        $repartitions = Repartition::where('correcteur_id','=',$id)->get();
        foreach ($repartitions as $rep) {
            $rep->correcteur_id = Auth::user()->id;
            $rep->save();
        }

        if ($user != null) {
            $user->delete();
        }
        return view('admin.manage-users');
    }

    public function promoteAdmin($id) {
        $user = User::find($id);
        if ($user != null) {
            $admin = new Admin();
            $admin->user()->associate($user);
            $admin->save();
        }
        return view('admin.manage-users');
    }

    public function addUE($id,Request $request) {
        $data = $request->all();
        $user = User::find($id);
        if ($user != null) {
            UserController::setResponsableUE($user,$data['ue']);
        }
        return view('admin.manage-users');
    }
}