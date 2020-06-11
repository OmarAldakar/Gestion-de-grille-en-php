<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UE;
use App\Repartition;
use App\Eleve;
use App\User;
use App\Exercice;
use App\Grille;
use Illuminate\Support\Facades\DB;
use App\CorrectionGrille;
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
        $users = UserController::getConfirmed()->pluck('id');
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

    public static function getUesByCorrecteur($correcteur_id) {
        $exercices = Repartition::where('correcteur_id','=',$correcteur_id)->pluck('exercice_id');
        $ues = Exercice::select("ue_id")->distinct()->whereIn('id',$exercices)->pluck('ue_id');
        return UE::find($ues);
    }

    public static function isCorrecteur($correcteur_id,$ue_id) {
        return RepartitionController::getUesByCorrecteur($correcteur_id)->find($ue_id) != null;
    }

    public static function getExercices($correcteur_id) {
        $exercices = Repartition::where('correcteur_id','=',$correcteur_id)->pluck('exercice_id');
        return Exercice::find($exercices);
    }

    public static function getGrilles($correcteur_id,$exercice_id) {
        $exercices = Repartition::where('correcteur_id','=',$correcteur_id)
                                ->where('exercice_id','=',$exercice_id)
                                ->pluck('grille_id');
        return Grille::find($exercices);
    }

    public static function getEleves($correcteur_id,$exercice_id,$grille_id) {
        $rep = Repartition::where('correcteur_id','=',$correcteur_id)
                                ->where('exercice_id','=',$exercice_id)
                                ->where('grille_id','=',$grille_id)
                                ->first();
        return $rep->eleves()->get();
    }

    public static function getGrilleCorr($correcteur_id,$exercice_id,$grille_id,$eleve_id) {
        return Repartition::join('repartition_eleve','id','repartition_id')
                                ->where('exercice_id','=',$exercice_id)
                                ->where('grille_id','=',$grille_id)
                                ->where('correcteur_id','=',$correcteur_id)
                                ->where('eleve_id','=',$eleve_id)
                                ->first()->grille_corr_id;
    }

    public static function haveCorrection($correction,$user_id) {
        $rep = DB::table('repartition_eleve')->where('grille_corr_id',"=",$correction)
                        ->first();
        
        return $rep != null && Repartition::find($rep->repartition_id) != null && Repartition::find($rep->repartition_id)->correcteur_id == $user_id;
    }

    public static function getEleveByCorrection($correction_id) {
        $eleves = DB::table('repartition_eleve')->where('grille_corr_id',"=",$correction_id)->pluck('eleve_id');
        return Eleve::find($eleves);
    }

    public static function getEleveNonAssociatedByCorrection($correction_id) {
        $associated = RepartitionController::getEleveByCorrection($correction_id);
        $rep_eleve = DB::table('repartition_eleve')->where('grille_corr_id',"=",$correction_id)->first();
        if ($rep_eleve != null) {
            $all = Repartition::find($rep_eleve->repartition_id)->eleves()->get();
            return $all->diff($associated);
        }
        return [];
    }

    public static function getNiveau($exercice,$grille,$critere,$eleve) {
        $rep = Repartition::where('grille_id','=',$grille->id)->where('exercice_id','=',$exercice->id)
                        ->join('repartition_eleve','repartition_id','id')->where('eleve_id','=',$eleve->id)->first();
        if ($rep != null) {
            $correction = CorrectionGrille::find($rep->grille_corr_id);
            $critere_corr = $correction->criteres()->where('critere_id','=',$critere->id)->first();
            if ($critere_corr != null) {
                return $critere_corr->niveau;
            }
        }
        
        return 0;
    }

    public static function getBilan($ue) {

        $return = [];
        foreach($ue->exercices()->get() as $exercice) {
            $array = [];

            foreach ($exercice->grilles()->get() as $grille) {
                foreach($grille->criteres()->get() as $critere) {
                    $array = [];
                    $array['Exercice'] =  $exercice->titre;
                    $array['Grille'] = $grille->titre;
                    $array['Critere'] = $critere->libelle;
                    foreach ($ue->eleves()->get() as $eleve) {
                        $correction = RepartitionController::getNiveau($exercice,$grille,$critere,$eleve);
                        $key = $eleve->nom . " " . $eleve->prenom;
                        $array[$key] = $correction;
                    }
                    array_push($return,$array);
                }
            }
        }
        return $return;
    }

        /**
     *
     * Exports an associative array into a CSV file using PHP.
     *
     * @see https://stackoverflow.com/questions/21988581/write-utf-8-characters-to-file-with-fputcsv-in-php
     *
     * @param array     $data       The table you want to export in CSV
     * @param string    $filename   The name of the file you want to export
     * @param string    $delimiter  The CSV delimiter you wish to use. The default ";" is used for a compatibility with microsoft excel
     * @param string    $enclosure  The type of enclosure used in the CSV file, by default it will be a quote "
     */
    public static function export_data_to_csv($data,$filename='export',$delimiter = ',',$enclosure = '"')
    {
        // Tells to the browser that a file is returned, with its name : $filename.csv
        header("Content-disposition: attachment; filename=$filename.csv");
        // Tells to the browser that the content is a csv file
        header("Content-Type: text/csv");

        // I open PHP memory as a file
        $fp = fopen("php://output", 'w');

        // Insert the UTF-8 BOM in the file
        fputs($fp, $bom =( chr(0xEF) . chr(0xBB) . chr(0xBF) ));

        // I add the array keys as CSV headers
        fputcsv($fp,array_keys($data[0]),$delimiter,$enclosure);

        // Add all the data in the file
        foreach ($data as $fields) {
            fputcsv($fp, $fields,$delimiter,$enclosure);
        }

        // Close the file
        fclose($fp);

        // Stop the script
        die();
    }
}
