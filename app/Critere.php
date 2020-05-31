<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Critere extends Model
{
    protected $fillable = [
        'libelle','niveau1','niveau2','niveau3','grille_id'
    ];

    public function grille() {
        return $this->belongsTo(Grille::class,'grille_id');
    }
}
