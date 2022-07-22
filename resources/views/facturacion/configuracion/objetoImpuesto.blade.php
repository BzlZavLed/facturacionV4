@extends('dashboard')

@section('content')
    <main>
        <div class="container">
            <h5>ObjetoImpuesto_catalogo</h5>
            <form class="form-inline" method="GET">
                <div class="form-group">
                  <label for="filter">Buscar</label>
                  <input type="text" class="form-control" id="filter" name="filter" placeholder="Buscar concepto..." value="{{$filter}}">
                </div>
                <button type="submit" class="btn btn-default">Filtrar</button>
              </form><br>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <th>Id</th>
                        <th>Clave</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        @if ($objetoImpuesto->count() == 0)
                        <tr>
                            <td colspan="5">No hay conceptos</td>
                        </tr>
                        @endif
                
                        @foreach ($objetoImpuesto as $clave)
                        <tr>
                            <td>{{ $clave->id }}</td>
                            <td>{{ $clave->clave }}</td>
                            <td>{{ $clave->descripcion }}</td>
                            @if ($clave->estado == 0)
                            <td>
                                <form action="{{ route('updateObjetoImpuesto', ['id' => $clave->id,'value' => 1]) }}">
                                    <button type = "submit" class="btn btn-sm btn-success">Activar</button>
                                </form>
                            </td>
                            @else
                            <td>
                                <form action="{{ route('updateObjetoImpuesto', ['id' => $clave->id,'value' => 0]) }}">
                                    <button type = "submit" class="btn btn-sm btn-primary">Desactivar</button>
                                </form>
                                <p class="text-info">Activo</p>
                            </td>  
                            @endif
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
           
             {{ $objetoImpuesto->links() }}
            
            <p>
                Mostrando {{$objetoImpuesto->count()}} de {{ $objetoImpuesto->total() }} concepto(s).
            </p>
        </div>
    </main>
@endsection
