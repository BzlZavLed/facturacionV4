@extends('dashboard')

@section('content')
    <style>
        /* Absolute Center Spinner */
        .loading {
            position: fixed;
            z-index: 999;
            height: 2em;
            width: 2em;
            overflow: visible;
            margin: auto;
            top: 0;
            left: 0;
            bottom: 0;
            right: 0;
        }

        /* Transparent Overlay */
        .loading:before {
            content: '';
            display: block;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.3);
        }

        /* :not(:required) hides these rules from IE9 and below */
        .loading:not(:required) {
            /* hide "loading..." text */
            font: 0/0 a;
            color: transparent;
            text-shadow: none;
            background-color: transparent;
            border: 0;
        }

        .loading:not(:required):after {
            content: '';
            display: block;
            font-size: 10px;
            width: 1em;
            height: 1em;
            margin-top: -0.5em;
            -webkit-animation: spinner 1500ms infinite linear;
            -moz-animation: spinner 1500ms infinite linear;
            -ms-animation: spinner 1500ms infinite linear;
            -o-animation: spinner 1500ms infinite linear;
            animation: spinner 1500ms infinite linear;
            border-radius: 0.5em;
            -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
            box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
        }

        /* Animation */

        @-webkit-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-moz-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-o-keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes spinner {
            0% {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    </style>
    <main>
        <div class="loading" id="loading">Loading&#8230;</div>
        <div class="container">
            <h3>Consulta diarios contabilizados</h3>
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="fondos">Fondos</label>
                        <select name="fondo" id="fondo" class="form-control">
                            <option value="select" selected disabled>--Selecciona una opción</option>
                            @if (is_null($fondos))
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
                <div class="col-sm-3">
                    <label for="dateStart">Fecha de consulta</label>
                    <input type="date" class="form-control" name="dateStart" id="dateStart">

                </div>
                <div class="col-sm-3">
                    <label for="dateEnd">Fecha final</label>
                    <input type="date" class="form-control" name="dateEnd" id="dateEnd"><br>
                    <input type="button" name="submit" id="submit" class="btn btn-success" value="Consultar">
                </div>
            </div>
        </div>
        <div class="container">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="diariostable">
                </table>
            </div>
        </div>

    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

    <script type="text/javascript" src="{{ asset('js/tools.js') }}"></script>
    <script>
        $("#diariostable").on("click", "#facturarMovimiento", function() {
            var $conceptos = $(this).closest("tr"),
                $valores = $conceptos.find("td");
            let factura = {
                "JRNAL_TYPE": $valores[0].innerText,
                "JRNAL_NO": $valores[1].innerText,
                "JRNAL_LINE": $valores[2].innerText,
                "JRNAL_SRCE": $valores[3].innerText,
                "TRANS_DATETIME": $valores[4].innerText,
                "PERIOD": $valores[5].innerText,
                "ACCNT_CODE": $valores[6].innerText,
                "AMOUNT": $valores[7].innerText,
                "ANAL_T0": $valores[8].innerText,
                "ANAL_T1": $valores[9].innerText,
                "ANAL_T2": $valores[10].innerText,
                "ANAL_T3": $valores[11].innerText,
                "ANAL_T4": $valores[12].innerText,
                "ANAL_T5": $valores[13].innerText,
                "ANAL_T6": $valores[14].innerText,
                "ANAL_T7": $valores[15].innerText,
                "ANAL_T8": $valores[16].innerText,
                "ANAL_T9": $valores[17].innerText,
                "TREFERENCE": $valores[18].innerText,
                "DESCRIPTN": $valores[19].innerText
            }
            setCookie("facturaDiario", factura, 1, true);
            window.location.href = "{{ route("facturarGeneral") }}";

        })
    </script>
@endsection
