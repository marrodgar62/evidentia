<?php

namespace App\Http\Controllers;

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

class TareaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkroles:PRESIDENT|COORDINATOR|REGISTER_COORDINATOR|SECRETARY|STUDENT');
    }

    public function view($instance,$id)
    {
        $instance = \Instantiation::instance();
        $tarea = Tarea::find($id);
        $contadores = Contador::where(['tarea_id' => $id])->get();

        
        return view('tarea.view',
            ['instance' => $instance, 'tarea' => $tarea, 'contadores'=>$contadores]);
    }

    public function list()
    {
        $tareas = Tarea::where(['user_id' => Auth::id()])->get();
        $instance = \Instantiation::instance();

        

        return view('tarea.list',
            ['instance' => $instance, 'tareas' => $tareas]);
    }

     
    //CREATE


    public function create()
    {
        $instance = \Instantiation::instance();
        
        
        return view('tarea.createAndEditTarea', 
        [
            'route_publish' => route('tarea.publish',$instance),
            'instance' => $instance
            ]);
    }

    public function publish(Request $request)
    {
        
        return $this->new($request);
    }

    private function new($request)
    {
        
        $instance = \Instantiation::instance();

        $tarea = $this->new_tarea($request);

        //$this->save_files($request,$tarea);

        return redirect()->route('tarea.list',$instance)->with('success', 'Tarea creada con éxito.');

    }

    private function new_tarea($request)
    {

        $request->validate([
             'titulo' => 'required|min:5|max:255',
             'descripcion' => ['required',new MinCharacters(10),new MaxCharacters(20000)],
         ]);

        // datos necesarios para crear evidencias
        $user = Auth::user();

        // creación de una nueva evidencia
        $tarea = Tarea::create([
            'titulo' => $request->input('titulo'),
            'user_id' => $user->id,
            'descripcion' => $request->input('descripcion'),
            
        ]);

        // cómputo del sello
        $tarea->save();

        return $tarea;
    }
    
    private function save($request)
    {
        $instance = \Instantiation::instance();

        // evidencia desde la que hemos decidido partir
        $tarea_previous = Tarea::find($request->_id);

        // creamos la nueva evidencia a partir de la seleccionada para editar
        $tarea_new = $this->new_tarea($request);

        // evidencia cabecera en el flujo de ediciones (la última)
        $tarea_header = $tarea_previous->find_header_tarea();
        $tarea_header->last = false;
        $tarea_header->save();

        // apuntamos al final del flujo de ediciones
        $tarea_new->points_to = $tarea_header->id;
        $tarea_new->save();

        // copiamos ahora los archivos de la carpeta temporal a la nueva evidencia
        $this->save_files($request,$tarea_new);

        
        return redirect()->route('tarea.list', $instance)->with('success', 'tarea publicada con éxito.');
        

    }
}