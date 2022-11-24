@extends('layouts.app')

@section('title', 'Lista de Tareas')

@section('title-icon', 'fas fa-id-badge')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/{{$instance}}">Home</a></li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">

            

            <div class="card shadow-lg">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataset" class="table table-hover table-responsive">
                        <thead>
                        <tr>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">ID</th>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Titulo</th>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Descripcion</th>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Cantidad total</th>
                            <th>Herramientas</th>

                        </tr>
                        </thead>
                        <tbody>

                        @foreach($tareas as $tarea)
                            <tr>
                                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$tarea->id}}</td>
                                <td><a href="{{route('tarea.view',['instance' => $instance, 'id' => $tarea->id])}}">{{$tarea->titulo}}</a></td>
                                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$tarea->descripcion}}</td>
                                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$tarea->cantidad_total}}</td>
                                <td class="align-middle">
                                <a class="btn btn-primary btn-sm" href="{{route('tarea.view',['instance' => \Instantiation::instance(), 'id' => $tarea->id])}}">
                                <i class="fas fa-eye"></i>
                                <span class="d-none d-sm-none d-md-none d-lg-inline"></span>
                                </a>
                                <!--<a class="btn btn-info btn-sm" href="{{route('tarea.edit',['instance' => \Instantiation::instance(), 'id' => $tarea->id])}}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                <span class="d-none d-sm-none d-md-none d-lg-inline"></span>
                                </a>
                                <a class="btn btn-info btn-sm" href="{{route('tarea.remove',['instance' => \Instantiation::instance(), 'id' => $tarea->id])}}">
                                <i class="fas fa-trash-alt">
                                </i>
                                <span class="d-none d-sm-none d-md-none d-lg-inline"></span>
                                </a>-->
                                </td>
                                


                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

    @section('scripts')

        <script>
            $(document).ready(function(){
                countdown("{{\Carbon\Carbon::create(\Carbon\Carbon::now())->diffInSeconds(Config::upload_evidences_timestamp(),false)}}");
            });
        </script>

    @endsection

@endsection