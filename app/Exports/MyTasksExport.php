<?php

namespace App\Exports;

use App\Models\Tarea;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MyTasksExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $tareas = Tarea::where("user_id","=",Auth::id())->get();

        $res = collect();
        foreach($tareas as $tarea){

            if(Auth::User()->hasRole('STUDENT')) {

                $array = [
                    'Titulo' => strtoupper(trim($tarea->titulo)),
                    'Descripción' => strtoupper(trim($tarea->descripcion)),
                    'Cantidad Total' => strtoupper(trim($tarea->cantidad_total))
                ];

                $object = (object) $array;
                $res->push($object);
            }
        }
        return $res;
    }

    public function headings(): array
    {
        return [
            'Título',
            'Descripción',
            'Cantidad Total'
        ];
    }
}