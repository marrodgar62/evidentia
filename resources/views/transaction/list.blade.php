@extends('layouts.app')

@section('title', 'Lista de Transacciones')

@section('title-icon', 'fas fa-id-badge')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/{{$instance}}">Home</a></li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-8">

            <div class="row mb-3">
                <p style="padding: 5px 50px 0px 15px">Exportar tabla:</p>
                <div class="col-lg-1 mt-12">
                    <a href="{{route('evidence.list.export',['instance' => $instance, 'ext' => 'xlsx'])}}"
                       class="btn btn-info btn-block" role="button">
                        XLSX</a>
                </div>
                <div class="col-lg-1 mt-12">
                    <a href="{{route('evidence.list.export',['instance' => $instance, 'ext' => 'csv'])}}"
                       class="btn btn-info btn-block" role="button">
                        CSV</a>
                </div>
                <div class="col-lg-1 mt-12">
                    <a href="{{route('evidence.list.export',['instance' => $instance, 'ext' => 'pdf'])}}"
                       class="btn btn-info btn-block" role="button">
                        PDF</a>
                </div>
            </div>

            <div class="card shadow-lg">

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataset" class="table table-hover table-responsive">
                        <thead>
                        <tr>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">ID</th>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Motivo</th>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Tipo</th>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Cantidad</th>
                            <th class="d-none d-sm-none d-md-table-cell d-lg-table-cell">Fecha</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($transactions as $transaction)
                            <tr>
                                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$transaction->id}}</td>
                                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$transaction->reason}}</td>
                                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$transaction->type}}</td>
                                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$transaction->amount}}</td>
                                <td class="d-none d-sm-none d-md-table-cell d-lg-table-cell">{{$transaction->date}}</td>
                               

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="callout callout-info">
                        <h4>

                            Añadir Transacción

                        </h4>
                        
                            <a href="{{route('evidence.list.export',['instance' => $instance, 'ext' => 'csv'])}}"
                            class="btn btn-info btn-block" role="button">
                            Añadir nueva
                            </a>
                        
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
