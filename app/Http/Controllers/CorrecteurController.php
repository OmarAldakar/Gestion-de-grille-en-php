<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UE;
use App\Repartition;
use App\CorrectionGrille;
use App\Exercice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CorrecteurController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
        $this->middleware('correcteur');
    }

    public function index($ue_id) {
        return view("correcteur.index")->with('ue',UE::find($ue_id));
    }

    public function correction($ue_id,$corr_id,$ex_id){
        return view("correcteur.correction")->with('ue',UE::find($ue_id))
                                            ->with('correction',CorrectionGrille::find($corr_id))
                                            ->with('exercice',Exercice::find($ex_id));
    }

    public function modification($ue_id,$corr_id,$ex_id,Request $request) {
        $data = $request->all();
        $correction = CorrectionGrille::find($corr_id);
        // Data validation
        $rules = [
            'globale' => ['string', 'max:2000','nullable'],
        ];
        foreach ($correction->criteres()->get() as $key => $corr_critere ) {
            $critere = "critere".$key;
            $commentaire = "commentaire".$key;
    
            $rules[$commentaire] = ['nullable', 'string','max:2000'];
            $rules[$critere] = ['nullable', 'integer','min:0','max:3'];
        }

        $validator = Validator::make($data, $rules);
        $validator->validate();

        $correction->commentaire = $data['globale'];
        $correction->save();

        foreach ($correction->criteres()->get() as $key => $corr_critere ) {
            $critere = "critere".$key;
            $commentaire = "commentaire".$key;
    
            $corr_critere->commentaire = $data[$commentaire];
            if (array_key_exists($critere,$data)){
                $corr_critere->niveau = $data[$critere];
            }
            $corr_critere->save();
        }

        return $this->correction($ue_id,$corr_id,$ex_id);
    }

    public function disociate($ue_id,$corr_id,$ex_id,$eleve_id) {
        $correction = CorrectionGrille::find($corr_id);
        $id_copy = $correction->deep_copy();            
        $repartition = DB::table('repartition_eleve')->where('grille_corr_id',"=",$corr_id)
                                                     ->where('eleve_id',"=",$eleve_id)
                                                     ->update(['grille_corr_id'=>$id_copy]);

        if (RepartitionController::getEleveByCorrection($corr_id)->isEmpty()) {
            $delete = $correction->deep_delete();
            return $this->index($ue_id);
        }
        return $this->correction($ue_id,$corr_id,$ex_id);
    }

    public function associate($ue_id,$corr_id,$ex_id,Request $request) {
        $data = $request->all();

        // On récupère le triplet (exercice,grille,correcteur) concerné
        $repartition = DB::table('repartition_eleve') -> where('grille_corr_id',"=",$corr_id) -> first();
        // On récupère la liste des correction que l'on dissocie
        $grille_corr_concerner = DB::table('repartition_eleve')->where('repartition_id',"=",3)->whereIn('eleve_id',$data["eleves"])->pluck('grille_corr_id'); 
        
        foreach ($data["eleves"] as $eleve_id) {
            // Pour chaque élève on associe la nouvelle correction
            $change = DB::table('repartition_eleve')
                            ->where('repartition_id',"=",$repartition->repartition_id)
                            ->where('eleve_id',"=",$eleve_id)
                            ->update(['grille_corr_id'=>$corr_id]);
        }

        // On supprime les correction que l'on vient de dissocier si il n'y a plus d'association
        foreach (CorrectionGrille::find($grille_corr_concerner) as $correction ) {
            if (RepartitionController::getEleveByCorrection($correction->id)->isEmpty()) {
                $res = $correction->deep_delete();
            }
        }

        return $this->correction($ue_id,$corr_id,$ex_id);
    }
}
