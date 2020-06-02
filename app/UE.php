<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UE extends Model
{
    protected $fillable = [
        'nom', 'annee'
    ];

    public function exercices() {
        return $this->hasMany(Exercice::class,'ue_id');
    }

    public function responsables() {
        return $this->belongsToMany('App\User', 'ue_user', 'ue_id', 'user_id');
    }

    public function grilles() {
        return $this->belongsToMany('App\Grille', 'grille_ue', 'ue_id', 'grille_id');
    }

    public function eleves() {
        return $this->belongsToMany('App\Eleve', 'eleve_ue', 'ue_id', 'eleve_id');
    }
}
