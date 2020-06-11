<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectionCriteresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('correction_criteres', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('commentaire',2000)->nullable();
            $table->bigInteger('critere_id');
            $table->bigInteger('grille_corr_id');
            $table->tinyInteger('niveau');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('correction_criteres');
    }
}
