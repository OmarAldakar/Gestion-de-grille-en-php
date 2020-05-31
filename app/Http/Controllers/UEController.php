<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UE;
use App\Grille;
use App\Exercice;
class UEController extends Controller
{
    public static function getNonAssociatedGrille(UE $ue,Exercice $exercice) {
        return $ue -> grilles() ->get() -> diff ($exercice -> grilles() -> get());
    }
}
