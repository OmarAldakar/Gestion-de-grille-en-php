<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Repartition extends Model
{
    //
    public $timestamps = false;
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'exercice_id', 'correcteur_id','grille_id'
    ];
    
    public function eleves() {
        return $this->belongsToMany('App\Eleve', 'repartition_eleve', 'repartition_id', 'eleve_id');
    }

    public function deep_delete() {
        $corrections = DB::table('repartition_eleve')->where('repartition_id',"=",$this->id)->pluck('grille_corr_id');
        $deleted = DB::table('repartition_eleve')->where('repartition_id',"=",$this->id)->delete();
        foreach(CorrectionGrille::find($corrections) as $correction) {
            $deleted = $correction->deep_delete();
        }
        return $this->delete();
    }
}
