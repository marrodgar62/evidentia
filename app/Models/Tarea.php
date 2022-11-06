<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table="tareas";

    protected $fillable = [
        'id', 'user_id','titulo', 'descripcion', 'cantidad_total'
    ];

    /*
    * Obtener todas las transacciones
    */

    public function get_all_tareas(): \Illuminate\Support\Collection
    {
        $collection = collect();

        foreach($tareas as $tarea){
            $collection->push($tarea);
        }

        return $collection;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
