<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    protected $table="contadores";

    protected $fillable = [
        'id', 'tarea_id','titulo', 'hours', 'status', 'pause_datetime'
    ];

    /*
    * Obtener todas las transacciones
    */

    public function get_all_contadores(): \Illuminate\Support\Collection
    {
        $collection = collect();

        foreach($contadores as $contador){
            $collection->push($contador);
        }

        return $collection;
    }

    public function tarea()
    {
        return $this->belongsTo('App\Models\Tarea');
    }

}
