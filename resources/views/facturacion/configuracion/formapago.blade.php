@extends('dashboard')

@section('content')
    <main>
        <div class="container">
            <h5>FormaPago_catalogo</h5>
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
                        @if ($formaPago->count() == 0)
                        <tr>
                            <td colspan="5">No hay conceptos</td>
                        </tr>
                        @endif
                
                        @foreach ($formaPago as $clave)
                        <tr>
                            <td>{{ $clave->id }}</td>
                            <td>{{ $clave->clave }}</td>
                            <td>{{ $clave->descripcion }}</td>
                            @if ($clave->estado == 0)
                            <td>
                                <form action="{{ route('updateFormaPago', ['id' => $clave->id,'value' => 1]) }}">
                                    <button type = "submit" class="btn btn-sm btn-success">Activar</button>
                                </form>
                            </td>
                            @else
                            <td>
                                <form action="{{ route('updateFormaPago', ['id' => $clave->id,'value' => 0]) }}">
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
           
             {{ $formaPago->links() }}
            
            <p>
                Mostrando {{$formaPago->count()}} de {{ $formaPago->total() }} concepto(s).
            </p>
        </div>
    </main>
@endsection
