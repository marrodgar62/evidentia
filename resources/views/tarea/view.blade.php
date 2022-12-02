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
            
        <div class="card shadow-sm">

                <div class="card-body">

                    <h4>Estado</h4>

                    <p class="text-muted">Última edición
                        <b>{{ \Carbon\Carbon::parse($tarea->created_at)->diffForHumans() }}</b>
                    </p>

                    

                    <hr>

                    

                </div>

            </div>


        <div class="card shadow-lg">

<div class="card-body">
    <h4>Contadores</h4>
    <div class="card-body">
    <a class="btn btn-primary btn-sm" href="{{route('contador.createAndEditContador',['instance' => \Instantiation::instance()])}}">AÑADIR CONTADOR</a>
    </div>
    <div class="table-responsive">
        <table id="dataset" class="table table-hover table-responsive">
        <thead>
        <tr>
            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">ID</th>
            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Titulo</th>
            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Horas</th>
            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Status</th>
            <th>Herramientas</th>

        </tr>
        </thead>
        <tbody>

        @foreach($contadores as $contador)
            <tr>
                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$contador->id}}</td>
                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$contador->titulo}}</td>
                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$contador->hours}}</td>
                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$contador->status}}</td>
                <td class="align-middle">

                @if($contador->status == 'pausa')
                <a class="btn btn-primary btn-sm" href="{{route('contador.play',['instance' => \Instantiation::instance(), 'id' => $contador->id])}}">
                Comenzar
                </a>
                @endIf

                @if($contador->status== 'contando')
                <a class="btn btn-secondary btn-sm"  href="{{route('contador.pausa',['instance' => \Instantiation::instance(), 'id' => $contador->id])}}">
                Pausar
                </a>
                <a class="btn btn-secondary btn-sm"  href="{{route('contador.terminada',['instance' => \Instantiation::instance(), 'id' => $contador->id])}}">
                Terminar
                </a>
                @endif
            
                
                </td>
                


            </tr>
        @endforeach

        </tbody>
    </table>
    </div>
</div>

</div>


        

        


@endsection
