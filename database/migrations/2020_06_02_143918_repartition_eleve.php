<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RepartitionEleve extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repartition_eleve', function (Blueprint $table) {
            $table->bigInteger('repartition_id');
            $table->bigInteger('eleve_id');
            $table->primary(array('repartition_id','eleve_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repartition_eleve');
    }
}
