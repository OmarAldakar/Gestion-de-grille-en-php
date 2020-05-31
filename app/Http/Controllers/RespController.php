<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\UE;
use App\Exercice;
use App\Grille;
use App\Critere;

class RespController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('resp');
    }

    public function createGrilleView($ue_id) {
        return view('responsable.crea-grille', [
            "ue" => UE::find($ue_id)
        ]);
    }

    public function makeRule($i,$rules) {
        $crit = "critere.".$i;
        $niv1 = "niveau1.".$i;
        $niv2 = "niveau2.".$i;
        $niv3 = "niveau3.".$i;

        $rules[$crit] = ['required', 'string','max:255'];
        $rules[$niv1] = ['required', 'string','max:600'];
        $rules[$niv2] = ['required', 'string','max:600'];
        $rules[$niv3] = ['required', 'string','max:600'];

        return $rules;
    }

    public function isAllLineNull($i,$data) {
        return $data['critere'][$i] == null && $data['niveau1'][$i] == null 
                    && $data['niveau2'][$i] == null && $data['niveau3'][$i] == null;
    }

    public function isAllLineNonNull($i,$data) {
        return $data['critere'][$i] != null && $data['niveau1'][$i] != null 
                    && $data['niveau2'][$i] != null && $data['niveau3'][$i] != null;
    }

    public function sameLength($data) {
        $len1 = count($data['critere']);
        $len2 = count($data['niveau1']);
        $len3 = count($data['niveau2']);
        $len4 = count($data['niveau3']);

        return $len1 == $len2 && $len2 == $len3 && $len3 == $len4;
    }

    public function createGrille($ue_id,Request $request) {
        $data = $request->all();     

        // Data validation
        $rules = [
            'titre' => ['required', 'string', 'max:255'],
            'precision' => ['string','max:2000','nullable']
        ];

        if (!$this->sameLength($data)) {
            return view('responsable.crea-grille', ["ue" => UE::find($ue_id)]);
        }
        
        for ($i=0; $i < count($data['critere']) ; $i++) {
            if ($i == 0 || !$this->isAllLineNull($i,$data)) {
                $rules = $this->makeRule($i,$rules);
            }
        }

        $validator = Validator::make($data, $rules);
        $validator->validate();
        
        // Model creation
        $grille = Grille::create([
            "titre" => $data["titre"],
            "precision" => $data["precision"]
        ]);
        
        $grille -> ues()-> attach($ue_id);

        for ($i=0; $i < count($data['critere']) ; $i++) {
            if ($this->isAllLineNonNull($i,$data)) {
                $critere = Critere::create([
                    'libelle' => $data['critere'][$i],
                    'niveau1' => $data['niveau1'][$i],
                    'niveau2' => $data['niveau2'][$i],
                    'niveau3' => $data['niveau3'][$i],
                    'grille_id' => $grille->id
                ]);
            }
        }

        return $this->index($ue_id);
    }

    // Retourne la vue associé
    public function manageEleves() {
        return view("responsable.gestion-eleves");
    }

    // Retourne la vue associé
    public function index($id) {
        return view("responsable.index")->with("ue",UE::find($id));
    }

    // Crée un exercice à l'ue
    public function addExercice($id, Request $request) {
        $data = $request->all();
        
        $validator = Validator::make($data, [
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['string','max:2000','nullable']
        ]);
        $validator->validate();

        $exercice = Exercice::create([
            'titre' => $data['titre'],
            'description' => $data['description'],
            'ue_id' => $id
        ]);

        return $this->index($id);
    }

    // Supprime l'exercice $ex_id
    public function deleteExercice($ue_id,$ex_id) {
        $exercice = Exercice::find($ex_id);
        $exercice->grilles()->detach();
        $exercice->delete();

        return view('responsable.index')->with("ue",UE::find($ue_id));
    }

    // Retourne la vue associé
    public function detailGrille ($ue_id,$ex_id,$grille_id) {
        return view("responsable.detail-grille");
    }

    // Associe la grille donné en paramètre à ex_id et à l'ue
    public function associate($ue_id,$ex_id,Request $request) {
        // Récupère les paramètre de la requète
        $data = $request->all();
        if ($data['grille'] != null) {
            // RespMiddleware nous assure que $exercice et $ue sont non nul
            $exercice = Exercice::find($ex_id);
            $ue = UE::find($ue_id);

            // On associe la grille à l'exercice puis la grille à l'ue
            $exercice->grilles()->syncWithoutDetaching([$data['grille']]);
            $ue->grilles()->syncWithoutDetaching([$data['grille']]);
        }
        return view('responsable.index')->with("ue",UE::find($ue_id));
    }

    public function disassociate($ue_id,$grille_id) {
        //On récupère les objets $ue et $grille
        $ue = UE::find($ue_id);
        $grille = Grille::find($grille_id);
        if ($ue != null && $grille != null) {
            //On supprime l'association UE/grille
            $ue -> grilles()->detach($grille_id);
            //On supprime les association Exercices/grille
            foreach ($ue->exercices()->get() as $exercice) {
                $exercice -> grilles()->detach($grille_id);
            }

            // Si la grille n'est associé à aucune autre UE
            if ( $grille -> ues() -> get()->isEmpty()) {
                $grille->delete();
            }
        }
        return view('responsable.index')->with("ue",UE::find($ue_id));
    }
    
}
