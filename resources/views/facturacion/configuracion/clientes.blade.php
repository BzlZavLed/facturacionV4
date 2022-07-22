@extends('dashboard')

@section('content')
    <main>
        <div class="container">
            <h5>Clientes_catalogo</h5>
            <form class="form-inline" method="GET">
                <div class="form-group">
                  <label for="filter">Buscar</label>
                  <input type="text" class="form-control" id="filter" name="filter" placeholder="Buscar concepto..." value="{{$filter}}">
                </div>
                <button type="submit" class="btn btn-default">Filtrar</button>
            </form><br>
              <div class="table-responsive">
                <table class="table table-bordered table-hover" id="clientes-table">
                    <thead>
                        <th>id</th>
                        <th>nombreCliente</th>
                        <th>razonCliente</th>
                        <th>rfcCliente</th>
                        <th>emailCliente</th>
                        <th>usoCfdiCliente</th>
                        <th>DomicilioFiscalReceptor</th>
                        <th>personaFisicaCliente</th>
                        <th>bunit</th>
                        <th>acción</th>
                    </thead>
                    <tbody>
                        @if ($clientes->count() == 0)
                        <tr>
                            <td colspan="5">No hay clientes</td>
                        </tr>
                        @endif
                
                        @foreach ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->id }}</td>
                            <td class = "nombreCliente" data-name = "nombreCliente" data-action = "update" data-type="text" data-pk={{ $cliente->id }} data-value = {{ $cliente->nombreCliente }}>{{ $cliente->nombreCliente }}</td>    
                            <td class = "razonCliente" data-name = "razonCliente" data-action = "update" data-type="text" data-pk={{ $cliente->id }} data-value = {{ $cliente->razonCliente }}>{{ $cliente->razonCliente }}</td>
                            <td class = "rfcCliente" data-name = "rfcCliente" data-action = "update" data-type="text" data-pk={{ $cliente->id }} data-value = {{ $cliente->rfcCliente }}>{{ $cliente->rfcCliente }}</td>
                            <td class = "emailCliente" data-name = "emailCliente" data-action = "update" data-type="text" data-pk={{ $cliente->id }} data-value = {{ $cliente->emailCliente }}>{{ $cliente->emailCliente }}</td>
                            <td class = "usoCfdiCliente"  data-name = "usoCfdiCliente" data-action = "update" data-type="text" data-pk={{ $cliente->id }} data-value = {{ $cliente->usoCfdiCliente }}>{{ $cliente->usoCfdiCliente }}</td>
                            <td class = "DomicilioFiscalReceptor"  data-name = "DomicilioFiscalReceptor" data-action = "update" data-type="text" data-pk={{ $cliente->id }} data-value = {{ $cliente->DomicilioFiscalReceptor }}>{{ $cliente->DomicilioFiscalReceptor }}</td>
                            <td class = "personaFisicaCliente" data-type="select"  data-source='[{value: "moral", text: "moral"}, {value:"física", text: "fisica"}]'data-name = "personaFisicaCliente" data-action = "update" data-type="text" data-pk={{ $cliente->id }} data-value = {{ $cliente->personaFisicaCliente }}>{{ $cliente->personaFisicaCliente }}</td>
                            <td class = "bunit">{{ $cliente->bunit }}</td>
                            
                            <td>
                                <a class="btn btn-danger" onclick="return confirm('Are you sure?')" href="{{route('borrarCliente', ['id' => $cliente->id])}}">Borrar</a>
                            </td>
                            
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              </div>
           
             {{ $clientes->links() }}
            
            <p>
                Mostrando {{$clientes->count()}} de {{ $clientes->total() }} concepto(s).
            </p>
        </div>
    </main>
@endsection
