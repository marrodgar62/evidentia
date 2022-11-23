@extends('layouts.app')

@section('title', 'Crear Contador')

@section('title-icon', 'fas fa-exclamation')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="/{{ $instance ?? '' }}">Home</a></li>
    <li class="breadcrumb-item active">@yield('title')</li>
@endsection

@section('content')

    <form method="POST" enctype="multipart/form-data">
        @csrf

        <input type="hidden" name="removed_files" id="removed_files"/>

        <div class="row">

            <div class="col-lg-8">

                <div class="card shadow-sm">

                    <div class="card-body">

                        <div class="form-row">

                            <x-input col="5" attr="titulo" :value="$tarea->titulo ?? ''" label="titulo" description="Escribe un título que describa con precisión tu tarea (mínimo 5 caracteres)"/>
                        
                            

                            <div class="form-group col-md-3">
                                <label for="comittee">Tarea asociada</label>
                                <select id="comittee" class="selectpicker form-control @error('tarea') is-invalid @enderror" name="tarea" value="{{ old('tarea') }}" required autofocus>
                                    @foreach($tareas as $tarea)
                                        @isset($contador)
                                            <option {{$tarea->id == old('tarea') || $contador->tarea->id == $tarea->id ? 'selected' : ''}} value="{{$tarea->id}}">
                                        @else
                                            <option {{$tarea->id == old('tarea') ? 'selected' : ''}} value="{{$tarea->id}}">
                                                @endisset
                                                {!! $tarea->titulo !!}
                                            </option>
                                            @endforeach
                                </select>

                                <small class="form-text text-muted">Elige una tarea a la que quieres asociar tu contador.</small>

                                @error('tarea')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            
                            </div>
                            <div class="form-group col-md-4">
                            <button type="submit" formaction="{{$route_publish}}" class="btn btn-primary" data-toggle="modal" data-target="#modal-default"><i class="fas fa-external-link-square-alt"></i> &nbsp;Sí, crear contador</button>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            

        </div>

    </form>

    @section('scripts')

        <script>
            setInterval(function () {
                $(".filepond--file-info-main").each(function() {
                    var uri = $(this).text();
                    $( this ).text(decodeURI(uri));
                });
            },1);
            // plugins de interés
            FilePond.registerPlugin(FilePondPluginFileValidateSize);
            FilePond.registerPlugin(FilePondPluginFileValidateType);
            FilePond.create(
                document.querySelector('input[id="files"]'),
                {
                    maxFileSize: 50000000,
                    maxTotalFileSize: 200000000,
                    labelMaxTotalFileSizeExceeded: 'Tamaño total máximo excedido',
                    labelMaxFileSizeExceeded: 'El archivo es demasiado grande',
                    labelMaxFileSize: 'El tamaño máximo es de {filesize}',
                    labelMaxTotalFileSize: 'El tamaño máximo total es de {filesize}',
                    acceptedFileTypes: ['image/*','application/zip','application/x-7z-compressed','application/x-tar','application/msword','application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '.xlsx','application/pdf','application/x-rar-compressed','application/vnd.ms-powerpoint','application/vnd.oasis.opendocument.presentation','application/vnd.oasis.opendocument.spreadsheet','application/vnd.oasis.opendocument.text'],
                    labelFileTypeNotAllowed: 'Tipo de archivo no válido',
                    server: {
                        url: '{{route('upload.process',Instantiation::instance())}}',
                        process: {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                        },
                        load: (source, load, error, progress, abort, headers) => {
                            var request = new Request(decodeURI(source));
                            fetch(request).then(function(response) {
                                response.blob().then(function(myBlob) {
                                    load(myBlob);
                                    $(".filepond--file-info-main").each(function() {
                                        var uri = $(this).text();
                                        $( this ).text(decodeURI(uri));
                                    });
                                });
                            });
                            $(".filepond--file-info-main").each(function() {
                                var uri = $(this).text();
                                $( this ).text(decodeURI(uri));
                            });
                        },
                        remove: function(source, load, errorCallback) {
                            var filename = source.split('/').pop()
                            var url = location.origin + '/' + '{{\Instantiation::instance()}}' + '/tarea/upload/remove/' + filename;
                            var request = new Request(url);
                            fetch(request).then(function(response) {
                                console.log(response);
                            });
                            load();
                        },
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    },
                    files: [
                        @foreach(Filepond::getFilesFromTemporaryFolder() as $file_name)
                            {
                                source: '{{route('upload.load',['instance' => Instantiation::instance(), 'file_name' => $file_name])}}',
                                options: {
                                    type: 'local'
                                }
                            },
                        @endforeach
                    ]
                }
            );
        </script>

    @endsection

@endsection