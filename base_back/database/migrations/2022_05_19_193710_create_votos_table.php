<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votos', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedInteger('votante_id');
            $table->unsignedInteger('tipo_id');
            $table->unsignedInteger('partido_id');
            
            $table->date('fecha');
            $table->timestamps();

            $table->foreign('votante_id')->references('id')->on('votantes')->onDelete('cascade');         
            $table->foreign('tipo_id')->references('id')->on('tipos');
            $table->foreign('partido_id')->references('id')->on('partidos'); 


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votos');
    }
}
