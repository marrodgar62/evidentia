<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{

    protected $connection = 'base21';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->string('titulo');
            $table->string('descripcion');
            $table->float('cantidad_total')->default(0);
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
        Schema::dropIfExists('tareas');
    }
}