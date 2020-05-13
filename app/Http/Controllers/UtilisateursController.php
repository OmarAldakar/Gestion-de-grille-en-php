<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Utilisateur;
use Illuminate\Support\Facades\Hash;

class UtilisateursController extends Controller
{
    public function list() {
        $users = Utilisateur::all();
        return view('utilisateurs.index',[
            'users' => $users
        ]);
    }

    public function store() {
        $nom = request('nom');
        $prenom = request('prenom');
        $email = request('email');
        $password1 = request('password1');
        $password2 = request('password2');

        if ($password1 == $password2 && $nom != "" && $prenom != "" && $email != "" && $password1 != "") {
            $user = new Utilisateur();
            $user->name = $nom;
            $user->prenom = $prenom;
            $user->email = $email;
            $user->password = Hash::make($password1);
            $user->save();
            return back();
        }
        return back();
    }
}
