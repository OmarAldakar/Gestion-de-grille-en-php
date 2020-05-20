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
        $this->belongsToMany('App\UE', 'ue_user', 'ue_id', 'user_id');
    }
}
