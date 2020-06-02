<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    protected $fillable = [
        'nom','prenom','email'
    ];

    public function ues() {
        return $this->belongsToMany('App\UE', 'eleve_ue', 'eleve_id', 'ue_id');
    }

    public function repartitions() {
        return $this->belongsToMany('App\Repartition', 'repartition_eleve', 'eleve_id', 'repartition_id');
    }
}
