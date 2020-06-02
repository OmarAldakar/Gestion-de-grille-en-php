<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
