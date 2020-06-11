<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorrectionCritere extends Model
{
    //
    protected $fillable = [
        'commentaire','niveau','critere_id','grille_corr_id'
    ];

    public function correction_grille() {
        return $this->belongsTo(CorrectionGrille::class,'grille_corr_id');
    }

    public function critere() {
        return $this->belongsTo(Critere::class,'critere_id');
    }

}
