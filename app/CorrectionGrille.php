<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorrectionGrille extends Model
{
    protected $fillable = [
        'commentaire','grille_id'
    ];

    public function grille() {
        return $this->belongsTo(Grille::class,'grille_id');
    }

    public static function createFromGrille($grille_id) {
        $grille = Grille::find($grille_id);
        $corr_grille = CorrectionGrille::create(["grille_id" => $grille_id]);

        foreach ($grille->criteres()->get() as $critere) {
            $corr_critere = CorrectionCritere::create([
                'niveau'=> 0,
                'critere_id' => $critere->id,
                'grille_corr_id'=>$corr_grille->id
                ]);
        }
        return $corr_grille->id;
    }

    public function criteres() {
        return $this->hasMany(CorrectionCritere::class,'grille_corr_id');
    }

    public function deep_copy() {
        $corr_grille = CorrectionGrille::create(["grille_id" => $this->grille_id,"commentaire" => $this->commentaire]);
        foreach ($this->criteres()->get() as $critere) {
            $corr_critere = CorrectionCritere::create([
                'niveau'=> $critere->niveau,
                'critere_id' => $critere->critere_id,
                'grille_corr_id'=>$corr_grille->id,
                'commentaire' => $critere->commentaire
                ]);
        }
        return $corr_grille->id;
    }

    public function deep_delete() {
        foreach ($this->criteres()->get() as $critere) {
            $result = $critere->delete();
        }
        return $this->delete();
    }
}
