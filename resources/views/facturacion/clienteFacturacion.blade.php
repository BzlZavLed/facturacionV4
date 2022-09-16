@extends('dashboard')

@section('content')
    <main>
        <div class="container">
            <h5>Facturación</h5>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="fondos">Fondos</label>
                        <select name="fondos" id="fondos" class="form-control">
                            <option value="select" selected disabled>--Selecciona una opción</option>
                            @if(is_null($fondos))
                                <option value="0">No hay fondos</option>
                            @else
                            @foreach ($fondos as $item)
                                <option value="{{ $item['fondo'] }}-{{ $item['bunit'] }}">
                                    {{ $item['fondo'] }}-{{ $item['bunit'] }}
                                </option>
                            @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <h5>Receptor (Cliente)</h5>
                    <form id="createClienteForm">
                        <div class="form-group">
                            <input list="nombreClienteList" id="nombreCliente" name="nombreCliente" class="form-control"
                                placeholder="Nombre del cliente">
                            <datalist id="nombreClienteList">
                                @foreach ($clientes as $item)
                                    <option value="{{ $item->nombreCliente }}" idElement="{{ $item->id }}"
                                        label="{{ $item->nombreCliente }} - {{ $item->rfcCliente }}">
                                @endforeach
                            </datalist>
                        </div>
                        <div class="form-group">
                            <label for="razonCliente">Razón Social</label>
                            <input type="text" name="razonCliente" id="razonCliente" class="form-control" required>

                            <label for="rfcCliente">RFC</label>
                            <input type="text" name="rfcCliente" id="rfcCliente" class="form-control">
                            @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('RFC') }}</span>
                            @endif

                            <label for="emailCliente">Email</label>
                            <input list="emailClienteList" id="emailCliente" name="emailCliente" class="form-control">
                            <datalist id="emailClienteList">
                                @foreach ($clientes as $item)
                                    <option value="{{ $item->nombreCliente }}" idElement="{{ $item->id }}"
                                        label="{{ $item->emailCliente }} - {{ $item->rfcCliente }}">
                                @endforeach
                            </datalist>

                            <label for="personaFisicaCliente">Persona </label>
                            <select name="personaFisicaCliente" id="personaFisicaCliente" class="form-control">
                                <option value="fisica">Fisica</option>
                                <option value="moral">Moral</option>
                            </select>

                            <label for="usoCfdiCliente">Uso CFDI</label>
                            <select name="usoCfdiCliente" id="usoCfdiCliente" class="form-control">
                                @foreach ($usoCfdi as $item)
                                    <option value="{{ $item->codigo }}">{{ $item->codigo }}-{{ $item->descripcion }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="DomicilioFiscalReceptor">Domicilio fiscal receptor</label>
                            <input type="number" class="form-control" id="DomicilioFiscalReceptor"
                                name="DomicilioFiscalReceptor">

                            <label for="RegimenFiscalReceptor">Regimen </label>
                            <select id="RegimenFiscalReceptor" name="RegimenFiscalReceptor" class="form-control">
                                @foreach ($regimenFiscal as $item)
                                    <option value="{{ $item->clave }}">{{ $item->clave }} - {{ $item->descripcion }}
                                    </option>
                                @endforeach
                            </select><br>

                            <button type="submit" class="btn btn-primary" id="guardarCliente">Guardar cliente</button>
                            <span class="text-success" id="success-message"> </span>

                        </div>
                    </form>
                </div>
                <div class="col-sm-6">
                    <h5>Emisor</h5>
                    <form id='emisorForm'>
                        <div class="form-group">
                            <label for="razonSocialEmisor"> Razón Social</label>
                            <select id="razonSocialEmisor" name="razonSocialEmisor" class="form-control">
                                <option value="-" selected>--Seleccionar--</option>
                                @foreach ($emisor as $item)
                                    <option value="{{ $item->rfc_emisor }}" 
                                            rfc_emisor="{{ $item->rfc_emisor }}"
                                            regimen_emisor="{{ $item->regimen_emisor }}" 
                                            c_postal="{{ $item->c_postal }}">
                                            {{ $item->razon_emisor }} - {{ $item->nombreColegio }}
                                    </option>
                                @endforeach
                            </select><br>

                            <label for="rfc_emisor">RFC </label>
                            <input type="text" id="rfc_emisor" name="rfc_emisor" class="form-control" value="">

                            <label for="regimen_emisor">Regimen </label>
                            <input type="text" id="regimen_emisor" name="regimen_emisor" class="form-control"
                                value="">

                            <label for="c_postal">Domicilio fiscal emisor</label>
                            <input type="number" id="c_postal" name="c_postal" class="form-control" value="">

                        </div>
                    </form>
                </div>
            </div>
            <hr>
            <div class="row">
                <h5>Datos generales</h5>
                <form id="datosGenerales">
                    <div class="col-sm-3">
                        <label for="datetimepicker">Fecha timbre</label>
                        <div class='input-group date' id='datetimepicker' style="display:flex">
                            <input type='text' id="datetimepickerinput" name="datetimepickerinput"
                                class="form-control" style="width: 50%" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar" style="font-size: 9px;"></span>
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label for="formaPago">Forma de pago</label>
                        <select id="formaPago" name="formaPago" class="form-control">
                            @foreach ($formaPago as $item)
                                <option value="{{ $item->clave }}">{{ $item->clave }} - {{ $item->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="tipoComprobante">Tipo de comprobante </label>
                        <select id="tipoComprobante" name="tipoComprobante" class="form-control">
                            @foreach ($tipoComprobante as $item)
                                <option value="{{ $item->clave }}">{{ $item->clave }} - {{ $item->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="metodoPagp">Metodo de pago </label>
                        <select id="metodoPago" name="metodoPago" class="form-control">
                            @foreach ($metodoPago as $item)
                                <option value="{{ $item->clave }}">{{ $item->clave }} - {{ $item->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label for="monedaPago">Moneda</label>
                        <select id="monedaPago" name="monedaPago" class="form-control">
                            @foreach ($moneda as $item)
                                <option value="{{ $item->clave }}">{{ $item->clave }} - {{ $item->descripcion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
            <hr>
            <div class="row">
                <h5>CFDI relacionados</h5>
                <form id="cfdiRelacionado">
                    <div class="col-sm-4">
                        <label for="cfdiRelacionado">
                            Tipo de relacion
                        </label>
                        <select id="cfdiRelacionado" name="cfdiRelacionado" class="form-control">
                            <option value="-" selected>--Sin relacion--</option>
                            @foreach ($tipoRelacion as $item)
                                <option value="{{ $item->clave }}">{{ $item->clave }} - {{ $item->descripcion }}
                                </option>
                            @endforeach
                        </select><br>
                    </div>
                    <div class="col-sm-4">
                        <label for="xmlRelacionado">UUID</label>
                        <input type="text" name="uuidRelacionado" id="uuidRelacionado" class="form-control">
                    </div>
                </form>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-6">
                    <label for="conceptoFacturacion">Concepto Facturación </label>
                    <input list="conceptoFacturacionList" id="conceptoFacturacion" name="conceptoFacturacion"
                        class="form-control">
                    <datalist id="conceptoFacturacionList">
                        @foreach ($concepto_internos as $item)
                            <option value="{{ $item->descripcionConcepto }}"
                                label="{{ $item->id }} - {{ $item->descripcionConcepto }}"
                                claveProdServ="{{ $item->claveProductoServicio }}"
                                claveUnidadFacturacion="{{ $item->claveUnidadFacturacion }}"
                                cuentas="{{ $item->cuentasContables }}"
                                numeroIdentificacion="{{ $item->numeroIdent }}" id="{{ $item->id }}">
                        @endforeach
                    </datalist>
                </div>
                <div class="col-sm-6">
                    <label for="objImp">Objeto a impuestos </label>
                    <select id="objImp" name="objImp" class="form-control">
                        @foreach ($objImp as $item)
                            <option value="{{ $item->clave }}">{{ $item->clave }} - {{ $item->descripcion }}
                            </option>
                        @endforeach
                    </select><br>
                </div>
            </div>
            <hr>
            <div class="row">
                <h4>Conceptos</h4>
                <form id="conceptosFactura" name="conceptosFactura">
                    <div class="col-sm-4">
                        <label for="claveProductoServicio">ClaveProductoServicio </label>
                        <input list="claveProductoServicioList" id="claveProductoServicio" name="claveProductoServicio"
                            class="form-control" readonly>
                        {{-- <datalist id="claveProductoServicioList">
                            @foreach ($claveProdServ as $item)
                                <option value="{{ $item->clave }}"
                                    label="{{ $item->clave }} - {{ $item->descripcion }}">
                            @endforeach
                        </datalist>

                        <p id="selectedclaveProductoServicio"></p> --}}

                        <label for="noIdentificacion"> NoIdentificacion</label>
                        <input type="text" id="noIdentificacion" name="noIdentificacion" class="form-control"
                            readonly>

                        <label for="cantidad">Cantidad</label>
                        <input type="number" id="cantidadConcepto" name="cantidadConcepto" class="form-control">
                        <br>
                    </div>
                    <div class="col-sm-4">
                        <label for="claveUnidadFacturacion">ClaveUnidad</label>
                        <input list="claveUnidadFacturacionList" id="claveUnidadFacturacion"
                            name="claveUnidadFacturacion" class="form-control" readonly>
                        {{-- <datalist id="claveUnidadFacturacionList">
                            @foreach ($claveunidad as $item)
                                <option value="{{ $item->clave }}"
                                    label="{{ $item->clave }} - {{ $item->descripcion }}">
                            @endforeach
                        </datalist>
                        <p id="selectedclaveUnidadFacturacion"></p> --}}
                        <label for="descripcionConcepto">Descripción</label>
                        <input type="text" id="descripcionConcepto" name="descripcionConcepto" class="form-control"
                            readonly>

                        <label for="valorUnitario">Valor Unitario</label>
                        <input type="number" id="valorUnitario" name="valorUnitario" class="form-control">

                    </div>
                    <div class="col-sm-4">

                        <label for="importeConcepto">Importe</label>
                        <input type="number" id="importeConcepto" name="importeConcepto" class="form-control">

                        <label for="descuentoConcepto">Descuento </label>
                        <input type="number" id="descuento" name="descuento" class="form-control"><br>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-primary" id="addConcept"><i
                                    class="fa-solid fa-circle-plus"></i>
                                Concepto</button>
                        </div>
                    </div>
                </form>
                {{-- <div id="impuestos" class="row" style="display: none">
                    <label>Impuestos</label>
                    <form id="desgloseImpuestos" name="desgloseImpuestos">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="base">Base</label>
                                <input type="number" class="form-control" id="base" name="Base">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="Impuesto">Impuesto</label>
                                <select name="Impuesto" id="impuesto" class="form-control">
                                    @foreach ($tipoImpuesto as $item)
                                        <option value="{{ $item->clave }}">{{ $item->clave }} -
                                            {{ $item->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="TipoFactor">TipoFactor</label>
                                <select name="TipoFactor" id="tipoFactor" class="form-control">
                                    <option value="Tasa">Tasa</option>
                                    <option value="Cuota">Cuota</option>
                                    <option value="Exento">Exento</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="TasaOCuota">TasaOCuota</label>
                                <input type="number" name="TasaOCuota" id="tasaOCuota" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="Importe">Importe calcular 16% automaticamente</label>
                                <input type="number" class="form-control" id="importe" name="Importe">
                            </div>
                        </div>
                    </form>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-hover" id="conceptosTable">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>ClaveProdServ</th>
                                    <th>NoIdentificacion</th>
                                    <th>Cantidad</th>
                                    <th>ClaveUnidad</th>
                                    <th>Descripcion</th>
                                    <th>ValorUnitario</th>
                                    <th>Importe</th>
                                    <th>Descuento</th>
                                    {{-- <th>ObjetoImpuestos</th> --}}
                                    <th>Acción</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
                {{-- <div class="col-sm-12" id="impuestosTable" style="display: none">
                    <div class="table-responsive">
                        <table class="table table-hover" id="impuestosTabla">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Base</th>
                                    <th>Impuesto</th>
                                    <th>TipoFactor</th>
                                    <th>TasaOCuota</th>
                                    <th>Importe</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div> --}}
                <div class="col-sm-3 offset-md-9">
                    <div class="table-responsive">
                        <table class="table table-hover" id="impuestosTable">
                            <tbody>
                                <tr>
                                    <th>Subtotal</th>
                                    <td id="subtotalFactura"></td>
                                </tr>
                                <tr>
                                    <th>Descuentos</th>
                                    <td id="descuentosTotal"></td>
                                </tr>
                                {{-- <tr>
                                    <th>Impuestos</th>
                                    <td id="iva"></td>
                                </tr> --}}
                                <tr>
                                    <th>Total</th>
                                    <td id="totalFactura"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-sm-3">
                    <label>Addenda</label>
                    <select name="tipoAddenda" id="tipoAddenda" class="form-control">
                        <option value="select" selected disabled>--Selecciona una opción--</option>
                        <option value="educativa">Colegiaturas</option>
                        <option value="ventas">Ventas</option>
                        <option value="donativos">Donativos</option>
                    </select>
                </div>
                <div id="colegiaturaAddenda" style="display: none" class="col-sm-9">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="alumnoaddenda">Alumno</label>
                            <input type="text" class="form-control" id="alumnoaddenda" list="listAddendas">
                            <datalist id="listAddendas">
                            </datalist>
                        </div>
                        <div>
                            <input type="button" name="addAddendaColegiatura" id="addAddendaColegiatura"
                                class="btn btn-default" value="Agregar">
                        </div>

                    </div>
                </div>
            </div><br><br>
            <div class="row" style="display: none" id="tableaddendadiv">
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table table-hover" id="addendasTableColegiatura">
                            <thead>
                                <tr>
                                    <th>Curp</th>
                                    <th>RVOE</th>
                                    <th>Nivel</th>
                                    <th>Alumno</th>
                                    <th>RFC</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <button class="btn btn-success" id="btnGenerarXml">Timbrar</button>
                </div>
            </div>
            <div class="row" id="descargables" style="display: none">
                <br><br>
                <label for="descargables">Descargables</label>
                <a href="" id="descargarXml" class = 'btn btn-default' target="_blank" download>Descargar XML</a>
                <a href="" id="descargarPdf" class = 'btn btn-secondary' target="_blank" download>Descargar PDF</a>
            </div>
        </div>
        <div class="lds-dual-ring">
            <!-- Place at bottom of page -->
        </div>
    </main>
    {{-- Searchable functions for elements in client forms --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script type="text/javascript" src="{{ asset('js/tools.js') }}"></script>
    <script>
        var diario = getCookie('facturaDiario');
        if(diario){
            diario = JSON.parse(diario);
            console.log(diario);
        
            var cuentas = {!! json_encode($concepto_internos->toArray(), JSON_HEX_TAG) !!};
            var selectIndex = null;
            cuentas.forEach(function callback(value, index) {
                var cuentasInternas = value.cuentasContables.split(',');
                if(cuentasInternas.includes(diario.ANAL_T1)){
                    selectIndex = index;
                }
            });
            $("#claveProductoServicio").val(cuentas[selectIndex].claveProductoServicio);
            $("#noIdentificacion").val(cuentas[selectIndex].numeroIdent);
            $("#claveUnidadFacturacion").val(cuentas[selectIndex].claveUnidadFacturacion);
            $("#descripcionConcepto").val(cuentas[selectIndex].descripcionConcepto);
            (diario.AMOUNT < 0) ? $("#importeConcepto").val(diario.AMOUNT*-1) : $("#importeConcepto").val(diario.AMOUNT);
            $("#datetimepickerinput").val(diario.TRANS_DATETIME);
            
            console.log(cuentas[selectIndex]);
        }
        var clientes = {!! json_encode($clientes->toArray(), JSON_HEX_TAG) !!};
        setCookie("bunit",@json(Auth::user()->bunit_account),2);
        
        /**
         * Get json from SEAWEBSERVICE for colegiaturas addendas
         */
        $("#fondos").on("change", function(event) {
            var fondoData = $(this).val();
            if (fondoData) {
                var data = fondoData.split("-");
                var fondo = data[0];
                var bunit = data[1];
                event.preventDefault();
                clearForm("createClienteForm");
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                });
                $.ajax({
                    url: "http://200.188.154.68:8086/BlueSystem/db/consultas/wsAddenda.php",
                    type: "POST",
                    dataType: "json",
                    data: {
                        bunit: bunit,
                        fondo: fondo,
                        id_proc: "clientes"
                    },
                    success: function(response) {
                        console.log("newSetOfClients "+bunit);
                        response.forEach(element => {
                            var newElement = {};
                            newElement.id = parseInt(element.ID);
                            newElement.nombreCliente = element.Nombre != false ? element.Nombre
                                .replace(/['"]+/g, '') : "";
                            newElement.razonCliente = element.Razon != false ? element.Razon
                                .replace(/['"]+/g, '') : "";
                            newElement.rfcCliente = element.RFC.slice(1,3).toUpperCase() == "XX" || element.RFC.slice(1,3).toUpperCase() == "XA" ? "XAXX010101000":element.RFC.replace(/['"]+/g, '').toUpperCase();
                            newElement.emailCliente = element.EmailFact.replace(/['"]+/g,
                                '');
                            newElement.DomicilioFiscalReceptor = element
                                .DomicilioFiscalReceptor;
                            newElement.personaFisicaCliente = "";
                            newElement.bunit = bunit;
                            newElement.usoCfdiCliente = "";
                            newElement.RegimenFiscalReceptor = element
                                .RegimenFiscalReceptor;
                            clientes.push(newElement);


                        });
                        $("#nombreClienteList").html("");
                        var options = "";
                        clientes.forEach(element => {
                            options += "<option value='" + element.nombreCliente +
                                "' idElement='" + element.id + "'>" + element.razonCliente +
                                "-" + element.rfcCliente + "</option>";
                        });
                        $("#nombreClienteList").html(options);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });


            }
        });

        $("#nombreCliente").on('input', function() {
            var datavalue = $('#nombreClienteList').find('option[value="' + $(this).val() + '"]').attr('idElement');
            var result = $.grep(clientes, function(e) {
                return e.id == datavalue;
            });
            var conceptosForma = document.forms['createClienteForm'];
            var index = 0;
            if (result.length > 0) {
                $.each(conceptosForma, function() {
                    var nameElement = conceptosForma.elements[index].name;
                    conceptosForma.elements[index].value = result[0][nameElement];
                    index++;
                });
            }

            //$("#guardarCliente").hide();

        })
        /*Opcion de buscar por email DESHABILITADA
        $("#emailCliente").on('input', function() {
            var clientes = {!! json_encode($clientes->toArray(), JSON_HEX_TAG) !!};
            var datavalue = $('#emailClienteList').find('option[value="' + $(this).val() + '"]').attr('idElement');
            var result = $.grep(clientes, function(e) {
                return e.id == datavalue;
            });
            var conceptosForma = document.forms['createClienteForm'];
            var index = 0;

            $.each(conceptosForma, function() {
                var nameElement = conceptosForma.elements[index].name;
                conceptosForma.elements[index].value = result[0][nameElement];
                index++;
            });
            //$("#guardarCliente").hide();
        })*/

        $("#conceptoFacturacion").on('input', function() {
            var conceptos = {!! json_encode($concepto_internos->toArray(), JSON_HEX_TAG) !!};
            var datavalue = $('#conceptoFacturacionList').find('option[value="' + $(this).val() + '"]').attr('id');
            var result = $.grep(conceptos, function(e) {
                return e.id == datavalue;
            });
            if (result.length > 0) {
                $("#claveProductoServicio").val(result[0]["claveProductoServicio"]);
                $("#claveUnidadFacturacion").val(result[0]["claveUnidadFacturacion"]);
                $("#descripcionConcepto").val(result[0]["descripcionConcepto"]);
                $("#noIdentificacion").val(result[0]["numeroIdent"]);
            }
        })
    </script>
@endsection
