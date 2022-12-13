<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateContadorTable extends Migration
{

    protected $connection = 'base21';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('contadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tarea_id')->references('id')->on('tareas')->onDelete('cascade');
            $table->string('titulo');
            $table->float('hours')->default(0);
            $table->enum('status',['pausa','contando','terminada']);
            $table->timestamp('pause_datetime')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contadores');
    }
}