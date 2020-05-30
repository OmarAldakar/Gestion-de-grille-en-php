<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RespController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function createGrille() {
        return view('responsable.crea-grille');
    }

    public function manageEleves() {
        return view("responsable.gestion-eleves");
    }
    
}
