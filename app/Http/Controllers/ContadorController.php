<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Exports\MyEvidencesExport;
use App\Models\Comittee;
use App\Models\Contador;

//use App\Models\Evidence;
//use App\Models\File;
//use App\Models\Proof;
use App\Models\Tarea;

use App\Rules\CheckHoursAndMinutes;
use App\Rules\MaxCharacters;
use App\Rules\MinCharacters;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

class ContadorController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkroles:PRESIDENT|COORDINATOR|REGISTER_COORDINATOR|SECRETARY|STUDENT');
    }

     
    //CREATE


    public function create()
    {
        $instance = \Instantiation::instance();
        $tareas = Tarea::where(['user_id'=>Auth::id()])->get();
       

        
        return view('contador.createAndEditContador', 
        [
            'route_publish' => route('contador.publish',$instance),
            'instance' => $instance,
            'tareas' => $tareas
            ]);
    }

    public function publish(Request $request)
    {
        
        return $this->new($request);
    }

    private function new($request)
    {
        
        $instance = \Instantiation::instance();

        $contador = $this->new_contador($request);


        return redirect()->route('tarea.view', ['id'=> $contador->tarea_id,'instance'=>$instance])->with('success', 'Contador creado con éxito.');

    }

    private function new_contador($request)
    {

        $request->validate([
             'titulo' => 'required|min:5|max:255',
         ]);

        // datos necesarios para crear evidencias
        $user = Auth::user();

        // creación de una nueva evidencia
        $contador = Contador::create([
            'titulo' => $request->input('titulo'),
            'tarea_id' => $request->input('tarea')

            
        ]);

        // cómputo del sello
        $contador->save();

        return $contador;
    }
    
    private function save($request)
    {
        $instance = \Instantiation::instance();

        // evidencia desde la que hemos decidido partir
        $contador_previous = Contador::find($request->_id);

        // creamos la nueva evidencia a partir de la seleccionada para editar
        $contador_new = $this->new_contador($request);

        // evidencia cabecera en el flujo de ediciones (la última)
        $contador_header = $contador_previous->find_header_contador();
        $contador_header->last = false;
        $contador_header->save();

        // apuntamos al final del flujo de ediciones
        $contador_new->points_to = $contador_header->id;
        $contador_new->save();

        // copiamos ahora los archivos de la carpeta temporal a la nueva evidencia
        $this->save_files($request,$contador_new);

        
        return redirect()->route('tarea.list', $instance)->with('success', 'Contador creado con éxito.');
        

    }

    //PLAY


    public function play($instance, $id)
    {
        $instance = \Instantiation::instance();

        $contador = Contador::find($id);
        $contador->status = 'contando';
        $contador->save();
        
        return redirect()->route('tarea.view', ['id'=> $contador->tarea_id,'instance'=>$instance])->with('success', 'El contador ha comenzado.');
    }



    public function pausa($instance, $id)
    {
        $instance = \Instantiation::instance();
        $contador = Contador::find($id);

        $contados = $contador->hours;
        $fechaNow = Carbon::now();
        $fechaUpdate = $contador->updated_at;

        $seg = $fechaUpdate->diffInSeconds($fechaNow);

        $total = ($contados)*3600 + $seg;
        
        

        $contador->hours= ($total)/3600;
        $contador->status = 'pausa';
        $contador->save();
        
        return redirect()->route('tarea.view', ['id'=> $contador->tarea_id,'instance'=>$instance])->with('success', 'El contador se ha pausado.');
    }

   
}