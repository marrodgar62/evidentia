@extends('layouts.app')

@section('title', 'Ver tarea: '.$tarea->titulo)

@section('title-icon', 'fab fa-battle-net')


@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/{{$instance}}">Home</a></li>
    
    <li class="breadcrumb-item"><a href="{{route('tarea.list',$instance)}}">Mis tareas</a></li>
    
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection
 
@section('content')

    <div class="row">

        <div class="col-lg-8">

            <div class="card shadow-lg">

                <div class="card-body">

                    <h5>
                        
                        <span class="badge badge-secondary">
                            <i class="far fa-clock"></i> {{$tarea->cantidad_total}} horas
                        </span>
                    </h5>

                    <h4>{{$tarea->titulo}}</h4>

                    <div class="post text-justify">

                        {!! $tarea->descripcion !!}

                        <br><br>


                        


                    </div>



                </div>

            </div>

        </div>

        <div class="col-lg-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <h4>Estado</h4>

                    <p class="text-muted">Última edición
                        <b>{{ \Carbon\Carbon::parse($tarea->created_at)->diffForHumans() }}</b>
                    </p>

                    

                    <hr>

                    

                </div>

            </div>

        </div>

    </div>


@endsection
