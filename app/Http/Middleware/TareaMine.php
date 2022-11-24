<?php

namespace App\Http\Middleware;

use App\Models\Tarea;
use Closure;
use Illuminate\Support\Facades\Auth;

class TareaMine
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $instance = \Instantiation::instance();

        $id = $request->route('id');
        if($id == null) // si se recibe por POST
        {
            $id = $request->_id;
        }
        $tarea = Tarea::find($id);

        if($tarea->user->id != Auth::id())
        {
            return redirect()->route('home',$instance);
        }

        return $next($request);
    }
}