@extends('dashboard')

@section('content')
    <main>
        <div class="container">
            <h5>Datos emisor</h5>
            <form id="emisorForm" name="emisorForm" method="POST" action="{{ route('guardarEmisor') }}">
                @csrf
                <!-- {{ csrf_field() }} -->
                <div class="row">
                    <div class="col-sm-3">
                        <label for="nombreColegio"> Nombre del colegio</label>
                        <input type="text" id="nombreColegio" name="nombreColegio" class="form-control">

                        <label for="razon_emisor"> Razón Social</label>
                        <input type="text" id="razon_emisor" name="razon_emisor" class="form-control">

                        <label for="rfc_emisor">RFC </label>
                        <input type="text" id="rfc_emisor" name="rfc_emisor" class="form-control">

                        <label for="regimen_emisor">Regimen </label>
                        <select id="regimen_emisor" name="regimen_emisor" class="form-control">
                            @foreach ($regimenFiscal as $item)
                                <option value="{{ $item->clave }}">{{ $item->clave }} - {{ $item->descripcion }}
                                </option>
                            @endforeach
                        </select><br>
                    </div>
                    <div class="col-sm-3">
                        <label for="c_postal">CP</label>
                        <input type="number" id="c_postal" name="c_postal" class="form-control">

                        <label for="bunit">BUNIT</label>
                        <input type="text" id="bunit" name="bunit" class="form-control">

                        <label for="email_emisor">Email</label>
                        <input type="email" id="email_emisor" name="email_emisor" class="form-control">

                        <label for="zona">Zona</label>
                        <input type="number" id="zona" name="zona" class="form-control">
                    </div>
                    <div class="col-sm-3">
                        <label for="versionDonataria">Version(Donataria)</label>
                        <select name="versionDonataria" id="versionDonataria" class="form-control">
                            <option value="1">1.0</option>
                        </select>

                        <label for="leyendaDonataria">Leyenda(Donataria)</label>
                        <textarea name="leyendaDonataria" id="leyendaDonataria" cols="30" rows="4" class="form-control"></textarea>
                    </div>
                    <div class="col-sm-3">
                        <label for="fechaDonataria">Fecha(Donataria)</label>
                        <input type="text" name="fechaDonataria" id="fechaDonataria" class="form-control">

                        <label for="permisoDonataria">Permiso(Donataria)</label>
                        <input type="text" name="permisoDonataria" id="permisoDonataria" class="form-control">
                        
                        <label for="permisoDonataria">Numero de certificado</label>
                        <input type="text" name="numeroCertificado" id="numeroCertificado" class="form-control"><br>

                        <button type="submit" class="btn btn-success offset-md-8" id="guardarCliente">Guardar</button>
                    </div>
                </div>
            </form>
            <hr>
            <div class="row">
                <form method="POST" enctype="multipart/form-data" id="upload-file" action="{{ url('storeFiles') }}">
                    @csrf
                    <h5>Archivos</h5>
                    <div class="col-sm-3">
                        <label for="rfc">RFC</label>
                        <input type="text" name="rfc" id="rfc" class="form-control">
                    </div>
                    <div class="col-sm-3">
                        <label for="certificado">Certificado</label>
                        <input type="file" name="certificates[]" id="certificado" class="form-control" accept=".cer" >
                        @error('file')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-3">
                        <label for="sello">Key</label>
                        <input type="file" name="certificates[]" id="sello" class="form-control" accept=".key" >
                        @error('file')
                            <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-sm-3">
                        <label for="password">Contraseña</label>
                        <input type="text" name="password" id="password" class="form-control"><br>
                        @if (session()->has('Success!'))
                            <div class="alert alert-success">
                                {{ session()->get('Success!') }}
                            </div>
                        @endif
                        @if (session()->has('Warning'))
                            <div class="alert alert-warning">
                                {{ session()->get('Warning') }}
                            </div>
                        @endif
                        <button type="submit" class="btn btn-primary" id="submit">Subir</button>
                    </div>
            </div>
            </form>
        </div><br>
        <div class="container-fluid">
            <div class="row">
                <div class="table-responsive" id="tableEmisor">
                    <table class="table table-bordered table-hover" style='font-family:"Courier New", Courier, monospace; font-size:80%'>
                        <thead>
                            <th>Razón Social</th>
                            <th>rfc_emisor</th>
                            <th>regimen</th>
                            <th>c_postal</th>
                            <th>bunit</th>
                            <th>email_emisor</th>
                            <th>zona</th>
                            <th>vDonat</th>
                            <th>leyendaDonataria</th>
                            <th>fechaDonataria</th>
                            <th>permisoDonataria</th>
                            <th>numeroCertificado</th>
                            <th>cert</th>
                            <th>key</th>
                            <th>Colegio</th>
                            <th>pass</th>
                            <th>acción</th>
                        </thead>
                        <tbody>
                            @if ($emisor->count() == 0)
                                <tr>
                                    <td colspan="5">No hay emisores</td>
                                </tr>
                            @endif

                            @foreach ($emisor as $clave)
                                <tr>
                                    <td>{{ $clave->nombreColegio }}</td>
                                    <td>{{ $clave->razon_emisor }}</td>
                                    <td>{{ $clave->rfc_emisor }}</td>
                                    <td>{{ $clave->regimen_emisor }}</td>
                                    <td>{{ $clave->c_postal }}</td>
                                    <td>{{ $clave->bunit }}</td>
                                    <td>{{ $clave->email_emisor }}</td>
                                    <td>{{ $clave->zona }}</td>
                                    <td>{{ $clave->versionDonataria }}</td>
                                    <td>{{ $clave->leyendaDonataria }}</td>
                                    <td>{{ $clave->fechaDonataria }}</td>
                                    <td>{{ $clave->permisoDonataria }}</td>
                                    <td>{{ $clave->numeroCertificado }}</td>
                                    <td>
                                        @if ($clave->path_cer != null)
                                            <a href="{{ asset($clave->name_cer)}}" target="_blank">{{$clave->name_cer}}<i class="fa fa-link"
                                                    aria-hidden="true"></i></a>
                                        @else
                                            Sin archivo
                                        @endif
                                    </td>
                                    <td>
                                        @if ($clave->path_key != null)
                                            <a href=" {{ asset($clave->name_key)}}" target="_blank">{{$clave->name_key}}<i class="fa fa-link"
                                                    aria-hidden="true"></i></a>
                                        @else
                                            Sin archivo
                                        @endif
                                    </td>
                                    <td>{{ $clave->password }}</td>
                                    <td>
                                        <button type="button" id="selectEmisor"
                                            class="btn btn-sm btn-primary">Seleccionar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </main>
@endsection
