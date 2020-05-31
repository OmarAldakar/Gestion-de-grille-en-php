<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercice extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titre', 'description','ue_id'
    ];


    public function ue() {
        return $this->belongsTo(UE::class,'ue_id');
    }

    public function grilles() {
        return $this->belongsToMany('App\Grille', 'exercice_grille', 'exercice_id', 'grille_id');
    }
}
