@extends('dashboard')

@section('content')
    <main>
        <div class="container">
            <h5>UsoCFDI</h5>
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
                        <th>P Moral</th>
                        <th>P Fisica</th>
                        <th>Estado</th>
                    </thead>
                    <tbody>
                        @if ($usoCfdi->count() == 0)
                        <tr>
                            <td colspan="5">No hay conceptos</td>
                        </tr>
                        @endif
                
                        @foreach ($usoCfdi as $clave)
                        <tr>
                            <td>{{ $clave->id }}</td>
                            <td>{{ $clave->codigo }}</td>
                            <td>{{ $clave->descripcion }}</td>
                            @if ($clave->p_moral == 1)
                            <td>
                                <form action="{{ route('updateUsoCfdi', ['id' => $clave->id,'value' => 0,'campo' => 'p_moral']) }}">
                                    <button type = "submit" class="btn btn-sm btn-success">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            </td>
                            @else
                            <td>
                                <form action="{{ route('updateUsoCfdi', ['id' => $clave->id,'value' => 0,'campo' => 'p_moral']) }}">
                                    <button type = "submit" class="btn btn-sm btn-danger">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                            @endif

                            @if ($clave->p_fisica == 1)
                            <td>
                                <form action="{{ route('updateUsoCfdi', ['id' => $clave->id,'value' => 0,'campo' => 'p_fisica']) }}">
                                    <button type = "submit" class="btn btn-sm btn-success">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                            </td>
                            @else
                            <td>
                                <form action="{{ route('updateUsoCfdi', ['id' => $clave->id,'value' => 0,'campo' => 'p_fisica']) }}">
                                    <button type = "submit" class="btn btn-sm btn-danger">
                                        <i class="fa fa-times" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </td>
                            @endif

                            
                            @if ($clave->estado == 0)
                            <td>
                                <form action="{{ route('updateUsoCfdi', ['id' => $clave->id,'value' => 1,'campo' => 'estado']) }}">
                                    <button type = "submit" class="btn btn-sm btn-default">Activar</button>
                                </form>
                            </td>
                            @else
                            <td>
                                <form action="{{ route('updateUsoCfdi', ['id' => $clave->id,'value' => 0, 'campo' => 'estado']) }}">
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
           
             {{ $usoCfdi->links() }}
            
            <p>
                Mostrando {{$usoCfdi->count()}} de {{ $usoCfdi->total() }} concepto(s).
            </p>
        </div>
    </main>
@endsection
