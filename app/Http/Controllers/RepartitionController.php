<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UE;
use App\Repartition;
use App\Eleve;
use App\User;

class RepartitionController extends Controller
{
    public static function getNonAssociatedEleve($exercice_id,$grille_id,$ue_id) {
        $ue_eleves = UE::find($ue_id)->eleves()->pluck('id');
        $associated = Repartition::join('repartition_eleve','id','repartition_id')
                                ->where('exercice_id','=',$exercice_id)
                                ->where('grille_id','=',$grille_id)
                                ->pluck('eleve_id');
        $non_associated = $ue_eleves->diff($associated);

        return Eleve::find($non_associated);
    }

    public static function getNonAssociatedCorrecteur($exercice_id,$grille_id) {
        $users = User::all()->pluck('id');
        $associated = Repartition::where('exercice_id','=',$exercice_id)->where('grille_id','=',$grille_id)->pluck('correcteur_id');
        $non_associated = $users->diff($associated);

        return User::find($non_associated);
    }

    public static function getAssociatedCorrecteur($exercice_id,$grille_id) {
        $associated = Repartition::where('exercice_id','=',$exercice_id)
                            ->where('grille_id','=',$grille_id)->pluck('correcteur_id');
        return User::find($associated);
    }

    public static function getCorrecteurEleve($exercice_id,$grille_id,$correcteur_id) {
        $rep = Repartition::where('exercice_id','=',$exercice_id)
                                ->where('grille_id','=',$grille_id)
                                ->where('correcteur_id','=',$correcteur_id)
                                ->first();

        return $rep->eleves()->get();
    }

}
