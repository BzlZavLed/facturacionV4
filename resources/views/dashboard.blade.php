<!DOCTYPE html>
<html>

<head>
    <title>Facturación SEA</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://kit.fontawesome.com/aa20e32489.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/css/bootstrap-datetimepicker.min.css">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css"
        rel="stylesheet">
    <link href="{{ asset('css/app.css') }}"
        rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-secondary">
        <a class="navbar-brand mr-auto" href="#">SEAFACTURACION v4</a>
        <b> Bienvenido &nbsp;{{ isset(Auth::user()->name) ? Auth::user()->name . Auth::user()->bunit_account: Auth::user()->email }}</b>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown"
            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav ml-auto">
                @auth
                    @if (Auth::user()->account_type == 1 || Auth::user()->account_type == 2)
                    
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('facturarGeneral') }}">Facturar</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('diarios-contabilizados') }}">Diarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('diarios-contabilizados') }}">Pagos de alumno</a>
                        </li>
                    @endif
                    @if (Auth::user()->account_type == 3)
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('diarios-contabilizados') }}">Pagos de alumno</a>
                        </li>
                    @endif
                    @if (Auth::user()->account_type == 1)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Configuración
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right bg-dark" aria-labelledby="navbarDropdownMenuLink">
                                <li class="hovermenu"><a class="nav-link" href="{{ route('conceptosInternos') }}">Conceptos
                                        SEA</a></li>
                                <li class="hovermenu"><a class="nav-link"
                                        href="{{ route('claveProdServ') }}">ClaveProductoServicio</a></li>
                                <li class="hovermenu"><a class="nav-link" href="{{ route('claveUnidad') }}">ClaveUnidad</a>
                                </li>
                                <li class="hovermenu"><a class="nav-link" href="{{ route('formaPago') }}">FormaPago</a></li>
                                <li class="hovermenu"><a class="nav-link" href="{{ route('metodoPago') }}">MetodoPago</a>
                                </li>
                                <li class="hovermenu"><a class="nav-link"
                                        href="{{ route('tipoComprobante') }}">TipoComprobante</a></li>
                                <li class="hovermenu"><a class="nav-link" href="{{ route('usoCfdi') }}">UsoCfdi</a></li>
                                <li class="hovermenu"><a class="nav-link"
                                        href="{{ route('tipoRelacion') }}">TipoRelación</a></li>
                                <li class="hovermenu"><a class="nav-link"
                                        href="{{ route('objetoImpuesto') }}">ObjetoImpuesto</a></li>
                                <li class="hovermenu"><a class="nav-link" href="{{ route('emisor') }}">Emisor</a></li>
                                <li class="hovermenu"><a class="nav-link" href="{{ route('clientes') }}">Clientes</a></li>
                            </ul>
                        </li>
                    @endif
                    @if (Auth::user()->account_type == 2)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Configuración
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right bg-dark" aria-labelledby="navbarDropdownMenuLink">
                                <li class="hovermenu"><a class="nav-link" href="{{ route('clientes') }}">Clientes</a></li>
                            </ul>
                        </li>
                    @endif
                @endauth
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('signout') }}">Salir</a>
                </li>
                
            </ul>
        </div>
    </nav>
    @yield('content')
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.7.14/js/bootstrap-datetimepicker.min.js">
</script>
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.js">
    /**Editable*/
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(function() {
        $('#datetimepicker').datetimepicker();
    });
</script>
<script type="text/javascript" src="{{ asset('js/tools.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/clienteFacturacion.controller.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/catalogos.controller.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/clienteConfiguracion.controller.js') }}"></script>

</html>
