<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

use App\UE;
use App\Exercice;
use App\Grille;
use App\Critere;
use App\Eleve;
use App\Repartition;
use App\CorrectionGrille;

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
    public function manageEleves($ue_id) {
        return view("responsable.gestion-eleves")->with('ue',UE::find($ue_id));
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

        //Supprime toute les repartitions ou cette exercice aparait
        $repartitions = Repartition::where('exercice_id','=',$ex_id)->get();
        foreach ($repartitions as $rep) {
            $deleted = $rep->deep_delete();
        }

        $exercice->grilles()->detach();
        $exercice->delete();

        return view('responsable.index')->with("ue",UE::find($ue_id));
    }

    // Retourne la vue associé
    public function detailGrille ($ue_id,$ex_id,$grille_id) {
        return view("responsable.detail-grille", [
            "ue" => UE::find($ue_id),
            "exercice" => Exercice::find($ex_id),
            "grille" => Grille::find($grille_id)
        ]);
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
                $repartitions = Repartition::where('exercice_id','=',$exercice->id)->where('grille_id',$grille_id)->get();
                foreach ($repartitions as $rep) {
                    $deleted = $rep->deep_delete();
                }
            }

            // Si la grille n'est associé à aucune autre UE
            if ( $grille -> ues() -> get()->isEmpty()) {
                $grille->delete();
            }
        }
        return view('responsable.index')->with("ue",UE::find($ue_id));
    }

    public function newEleve($data,$ue_id) {
        // Validate data
        $validator = Validator::make($data,[
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required','email']
        ] );
        $validator->validate();

        $eleve = Eleve::where('nom','=',$data['nom'])
                        ->where('prenom','=',$data['prenom'])
                        ->where('email','=',$data['email'])->first();
        
        if ($eleve == null) {
            $eleve = Eleve::create([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
            ]);
        }

        $eleve->ues()->syncWithoutDetaching([$ue_id]);
    }

    public function addEleve($ue_id,Request $request) {
        $data = $request->all();

        $this->newEleve($data,$ue_id);
        return $this->manageEleves($ue_id);
    }

    public function removeEleve($ue_id,$eleve_id, Request $request) {
        $ue = UE::find($ue_id);
        $ue->eleves()->detach($eleve_id);

        foreach($ue->exercices()->get() as $exercice) {
            foreach ($exercice->grilles()->get() as $grille) {
                $data = Repartition::join('repartition_eleve','id','repartition_id')
                                ->where('grille_id','=',$grille->id)
                                ->where('exercice_id','=',$exercice->id)
                                ->where('eleve_id','=',$eleve_id)->first();
                if($data != null) {
                    DB::table('repartition_eleve')->where('eleve_id','=',$data->eleve_id)
                                                    ->where('grille_corr_id','=',$data->grille_corr_id)
                                                    ->where('repartition_id','=',$data->repartition_id)->delete();
                    $correction = CorrectionGrille::find($data->grille_corr_id);
                    if (RepartitionController::getEleveByCorrection($correction->id)->isEmpty()) {
                        $deleted = $correction->deep_delete();
                    }
                }            
            }
        }

        return $this->manageEleves($ue_id);
    }
    
    public function importEleve($ue_id,Request $request) {
        $data = $request->all();

        if (array_key_exists('file',$data)) {
            $handle = fopen($data['file']->path(), "r");

            while ($csvLine = fgetcsv($handle, 1000, ",")) {
                if (count($csvLine) >= 3) {
                    $this->newEleve([
                        'nom' => $csvLine[0],
                        'prenom' =>$csvLine[1],
                        'email' => $csvLine[2],
                    ],$ue_id);
                }
            }
        }

        return $this->manageEleves($ue_id);
    }

    public function associateCorrecteur($ue_id,$ex_id,$grille_id,Request $request) {
        $data = $request->all();

        foreach($data['correcteurs'] as $correcteur_id) {
            $repartition = Repartition::where('exercice_id','=',$ex_id)
                                    ->where('grille_id','=',$grille_id)
                                    ->where('correcteur_id','=',$correcteur_id)->first();
            if ($repartition == null) {
                Repartition::create([
                    'exercice_id' => $ex_id,
                    'grille_id' => $grille_id,
                    'correcteur_id' => $correcteur_id
                ]);
            }
        }

        return $this->detailGrille($ue_id,$ex_id,$grille_id);
    }

    public function associateStudent($ue_id,$ex_id,$grille_id,$correcteur_id,Request $request) {
        $data = $request->all();
        $repartition = Repartition::where('exercice_id','=',$ex_id)
                                    ->where('grille_id','=',$grille_id)
                                    ->where('correcteur_id','=',$correcteur_id)->first();

        if ($repartition != null && $data['eleves'] != null) {
            foreach ($data['eleves'] as $eleve) {
                $corr_id = CorrectionGrille::createFromGrille($grille_id);
                DB::table('repartition_eleve')->insert([
                    ['repartition_id'=>$repartition->id,'eleve_id'=>$eleve,'grille_corr_id'=>$corr_id]
                ]);
            }
        }

        return $this->detailGrille($ue_id,$ex_id,$grille_id);
    }

    public function generateBilan($ue_id) {
        $data = RepartitionController::getBilan(UE::find($ue_id));
        RepartitionController::export_data_to_csv($data);
    }
}
