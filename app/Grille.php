<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grille extends Model
{

    protected $fillable = [
        'titre','precision'
    ];

    public function criteres() {
        return $this->hasMany(Critere::class,'grille_id');
    }

    public function exercices() {
        return $this->belongsToMany('App\Exercice', 'exercice_grille', 'grille_id', 'exercice_id');
    }

    public function ues() {
        return $this->belongsToMany('App\UE', 'grille_ue', 'grille_id', 'ue_id');
    }
}
