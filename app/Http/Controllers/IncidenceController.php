<?php

namespace App\Http\Controllers;

use App\Models\Comittee;
use App\Models\File;
use App\Models\Incidence;
use App\Models\IncidenceProof;
use App\Rules\MaxCharacters;
use App\Rules\MinCharacters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class IncidenceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('checkroles:PRESIDENT|COORDINATOR|REGISTER_COORDINATOR|SECRETARY|STUDENT');
    }

    public function view($instance,$id)
    {
        $instance = \Instantiation::instance();
        $incidence = Incidence::find($id);

        return view('incidence.view',
            ['instance' => $instance, 'incidence' => $incidence]);
    }

    public function list()
    {
        $instance = \Instantiation::instance();
        $comittees = Comittee::all();
        $incidence = Incidence::all();
        return view('incidence.list', 
        [
            'incidences' => $incidence,
            'instance' => $instance,
            'comittees'=> $comittees]);
    }
    public function create()
    {
        $instance = \Instantiation::instance();
        $comittees = Comittee::all();

        return view('incidence.createAndEditIncidence', 
        [
            'route_publish' => route('incidence.publish',$instance),
            'instance' => $instance,
            'comittees'=> $comittees]);
    }

    public function publish(Request $request)
    {
        return $this->new($request,"PENDING");
    }

    private function new($request,$status)
    {

        $instance = \Instantiation::instance();

        $incidence = $this->new_incidence($request,$status);

        $this->save_files($request,$incidence);

        return redirect()->route('incidence.list',$instance)->with('success', 'Evidencia creada con éxito.');

    }

    private function new_incidence($request,$status)
    {

        $request->validate([
            'title' => 'required|min:5|max:255',
            'description' => ['required',new MinCharacters(10),new MaxCharacters(20000)],
        ]);

        // datos necesarios para crear evidencias
        $user = Auth::user();

        // creación de una nueva evidencia
        $incidence = Incidence::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'status' => $status,
            'user_id' => $user->id,
            'comittee_id' => $request->input('comittee')
        ]);

        // cómputo del sello
        $incidence = \Stamp::compute_incidence($incidence);
        $incidence->save();

        return $incidence;
    }
    private function save_files($request, $incidence)
    {
        $user = Auth::user();
        $instance = \Instantiation::instance();
        $token = $request->session()->token();
        $tmp = $instance.'/tmp/'.$user->username.'/'.$token.'/';

        foreach (Storage::files($tmp) as $filename) {

            $name = pathinfo($filename, PATHINFO_FILENAME);
            $type = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $size = Storage::size($filename);
            $old_directory = $filename;
            $new_directory = $instance.'/incidence_proofs/'.$user->username.'/incidence_'.$incidence->id.'/'.$name.'.'.$type;

            try{
                // movemos
                Storage::move($old_directory, $new_directory);

                // almacenamos en la BBDD la información del archivo
                $file_entity = File::create([
                    'name' => $name,
                    'type' => $type,
                    'route' => $new_directory,
                    'size' => $size,
                ]);

                // cómputo del sello
                $file_entity = \Stamp::compute_file($file_entity);
                $file_entity->save();

                // almacenamos en la BBDD la información de la prueba de la evidencia
                $proof = IncidenceProof::create([
                    'incidence_id' => $incidence->id,
                    'file_id' => $file_entity->id
                ]);
            } catch (\Exception $e) {

            }

        }

        // ya no necesitamos la carpeta temporal, la eliminamos
        Storage::deleteDirectory($tmp);

    }
}
