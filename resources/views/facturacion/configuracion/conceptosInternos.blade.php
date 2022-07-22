@extends('dashboard')

@section('content')
    <main>
        <div class="container">
            <h5>Conceptos Internos SEA</h5>
            <form name="conceptoCreacion" action="{{ route('guardarConceptoInterno') }}" method="post">
                @csrf
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="claveProductoServicio">ClaveProductoServicio </label>
                        <input list="claveProductoServicioList" id="claveProductoServicio" name="claveProductoServicio"
                            class="form-control">
                        <datalist id="claveProductoServicioList">
                            @foreach ($claveProdServ as $item)
                                <option value="{{ $item->clave }}"
                                    label="{{ $item->clave }} - {{ $item->descripcion }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div class="form-group">
                        <label for="descripcionConcepto">Descripción</label>
                        <input type="text" id="descripcionConcepto" name="descripcionConcepto" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="numeroIdent">Numero de identificación para facturación</label>
                        <input type="text" id="numeroIdent" name="numeroIdent" class="form-control">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="cuentasContables">Escribe las cuentas contables asociadas a este concepto separadas por
                            coma</label>
                        <input type="text" name="cuentasContables" id="cuentasContables" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="claveUnidadFacturacion">ClaveUnidad</label>
                        <input list="claveUnidadFacturacionList" id="claveUnidadFacturacion" name="claveUnidadFacturacion"
                            class="form-control">
                        <datalist id="claveUnidadFacturacionList">
                            @foreach ($claveunidad as $item)
                                <option value="{{ $item->clave }}"
                                    label="{{ $item->clave }} - {{ $item->descripcion }}">
                            @endforeach
                        </datalist>
                    </div>
                    <div>
                        <button type="submit" value="Add student" class="btn btn-primary">Submit</button>
                    </div>
                </div>


            </form><br>
        </div>
        <div class="container">
            <form class="form-inline" method="GET">
                <div class="form-group">
                    <label for="filter">Buscar</label>
                    <input type="text" class="form-control" id="filter" name="filter"
                        placeholder="Buscar concepto..." value="{{ $filter }}">
                </div>
                <button type="submit" class="btn btn-default">Filtrar</button>
            </form><br>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <th>Id</th>
                        <th>claveProductoServicio</th>
                        <th>descripcionConcepto</th>
                        <th>cuentasContables</th>
                        <th>claveUnidadFacturacion</th>
                        <th>numeroIdentificación</th>
                        <th>acción</th>
                    </thead>
                    <tbody>
                        @if ($concepto_internos->count() == 0)
                            <tr>
                                <td colspan="7">No hay conceptos</td>
                            </tr>
                        @endif

                        @foreach ($concepto_internos as $clave)
                            <tr>
                                <td>{{ $clave->id }}</td>
                                <td>{{ $clave->claveProductoServicio }}</td>
                                <td>{{ $clave->descripcionConcepto }}</td>
                                <td>{{ $clave->cuentasContables }}</td>
                                <td>{{ $clave->claveUnidadFacturacion }}</td>
                                <td>{{ $clave->numeroIdent }}</td>
                                <td>
                                    <form action="{{ route('deleteConceptoInterno', ['id' => $clave->id]) }}">
                                        <button type="submit" class="btn btn-sm btn-primary">Borrar</button>
                                    </form>
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $concepto_internos->links() }}

            <p>
                Mostrando {{ $concepto_internos->count() }} de {{ $concepto_internos->total() }} concepto(s).
            </p>
        </div>
    </main>
@endsection
