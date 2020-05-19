<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercice extends Model
{
    public function ue() {
        return $this->belongsTo(UE::class,'ue_id');
    }
}
