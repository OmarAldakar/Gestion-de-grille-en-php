<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepartitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repartitions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('exercice_id');
            $table->bigInteger('correcteur_id');
            $table->bigInteger('grille_id');
            $table->unique(array('exercice_id','correcteur_id','grille_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repartitions');

    }
}
