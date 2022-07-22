<?php

namespace App\Http\Controllers;


use App\Models\Clientes;
use App\Models\Emisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ViewsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Vista facturacion general
     */
    public function facturarGeneral()
    {
        $usoCfdi = DB::Table('usocfdi_catalogo')
            ->where('estado', 1)
            ->get();
        $formaPago = DB::Table('formapago_catalogo')
            ->where('estado', 1)
            ->get();
        $tipoComprobante = DB::Table('tipocomprobante_catalogo')
            ->select('clave', 'descripcion')
            ->get();
        $metodoPago =  DB::Table('metodopago_catalogo')
            ->where('estado', 1)
            ->get();
        $moneda =  DB::Table('moneda_catalogo')
            ->select('clave', 'descripcion')
            ->get();
        $claveProdServ =  DB::Table('claveproductoservicio_catalogo')
            ->where('estado', 1)
            ->get();
        $claveunidad =  DB::Table('claveunidad_catalogo')
            ->where('estado', 1)
            ->get();
        $tipoRelacion = DB::Table('cfdirelacionados_catalogo')
            ->where('estado', 1)
            ->get();
        $objImp = DB::Table('objetoimp_catalogo')
            ->where('estado', 1)
            ->get();
        $tipoImpuesto = DB::Table('impuestos_catalogo')
            ->get();
        $concepto_internos = DB::Table('concepto_internos')
            ->get();
        $user = Auth::user();
        $clientes = DB::Table('clientes')->where('bunit', $user->bunit_account)->get();

        $emisor = DB::Table('emisor')->where('zona', $user->zona)->get();

        $fecha_timbre = Carbon::now()->toDateTimeString();
        return view('facturacion.clienteFacturacion', [
            'usoCfdi' => $usoCfdi,
            'formaPago' => $formaPago,
            'tipoComprobante' => $tipoComprobante,
            'emisor' => $emisor,
            'metodoPago' => $metodoPago,
            'moneda' => $moneda,
            'fecha_timbre' => $fecha_timbre,
            'claveProdServ' => $claveProdServ,
            'claveunidad' => $claveunidad,
            'tipoRelacion' => $tipoRelacion,
            'objImp' => $objImp,
            'clientes' => $clientes,
            'tipoImpuesto' => $tipoImpuesto,
            'concepto_internos' => $concepto_internos
        ]);
    }

    /**
     * Vistas y controladores menu Configuracion
     */
    public function claveProdServ(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $claveProdServ =  DB::Table('claveproductoservicio_catalogo')
                ->where('descripcion', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $claveProdServ =  DB::Table('claveproductoservicio_catalogo')
                ->select('id', 'clave', 'descripcion', 'estado')
                ->paginate(20);
        }
        return view('facturacion.configuracion.claveProductoServicio')->with('claveProdServ', $claveProdServ)->with('filter', $filter);
    }

    public function claveUnidad(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $claveUnidad =  DB::Table('claveunidad_catalogo')
                ->where('descripcion', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $claveUnidad =  DB::Table('claveunidad_catalogo')
                ->select('id', 'clave', 'descripcion', 'estado')
                ->paginate(20);
        }
        return view('facturacion.configuracion.claveUnidad')->with('claveUnidad', $claveUnidad)->with('filter', $filter);
    }

    public function formaPago(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $formaPago =  DB::Table('formapago_catalogo')
                ->where('descripcion', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $formaPago =  DB::Table('formapago_catalogo')
                ->select('id', 'clave', 'descripcion', 'estado')
                ->paginate(20);
        }
        return view('facturacion.configuracion.formaPago')->with('formaPago', $formaPago)->with('filter', $filter);
    }

    public function metodoPago(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $metodoPago =  DB::Table('metodopago_catalogo')
                ->where('descripcion', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $metodoPago =  DB::Table('metodopago_catalogo')
                ->select('id', 'clave', 'descripcion', 'estado')
                ->paginate(20);
        }
        return view('facturacion.configuracion.metodoPago')->with('metodoPago', $metodoPago)->with('filter', $filter);
    }

    public function tipoComprobante(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $tipoComprobante =  DB::Table('tipocomprobante_catalogo')
                ->where('descripcion', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $tipoComprobante =  DB::Table('tipocomprobante_catalogo')
                ->select('id', 'clave', 'descripcion', 'estado')
                ->paginate(20);
        }
        return view('facturacion.configuracion.tipoComprobante')->with('tipoComprobante', $tipoComprobante)->with('filter', $filter);
    }

    public function usoCfdi(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $usoCfdi =  DB::Table('usocfdi_catalogo')
                ->where('descripcion', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $usoCfdi =  DB::Table('usocfdi_catalogo')
                ->select('id', 'codigo', 'descripcion', 'p_moral', 'p_fisica', 'estado')
                ->paginate(20);
        }
        return view('facturacion.configuracion.usoCfdi')->with('usoCfdi', $usoCfdi)->with('filter', $filter);
    }

    public function tipoRelacion(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $tipoRelacion =  DB::Table('cfdirelacionados_catalogo')
                ->where('descripcion', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $tipoRelacion =  DB::Table('cfdirelacionados_catalogo')
                ->select('id', 'clave', 'descripcion', 'estado')
                ->paginate(20);
        }
        return view('facturacion.configuracion.tipoRelacion')->with('tipoRelacion', $tipoRelacion)->with('filter', $filter);
    }

    public function objetoImpuesto(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $objetoImpuesto =  DB::Table('objetoimp_catalogo')
                ->where('descripcion', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $objetoImpuesto =  DB::Table('objetoimp_catalogo')
                ->select('id', 'clave', 'descripcion', 'estado')
                ->paginate(20);
        }
        return view('facturacion.configuracion.objetoImpuesto')->with('objetoImpuesto', $objetoImpuesto)->with('filter', $filter);
    }

    /**
     * Vista configuracion-emisor catalogo
     */
    public function emisor()
    {
        $user = Auth::user();
        $bunit = $user->bunit_account;

        if ($bunit != 'S18') {
            $emisor = Emisor::leftJoin('files', 'emisor.rfc_emisor', '=', 'files.rfc')
                ->where('emisor.bunit', $user->bunit_account)
                ->get(['emisor.*', 'files.name_key', 'files.path_key', 'files.name_cer', 'files.path_cer', 'files.password']);
        } else {
            $emisor = Emisor::leftJoin('files', 'emisor.rfc_emisor', '=', 'files.rfc')
                ->get(['emisor.*', 'files.name_key', 'files.path_key', 'files.name_cer', 'files.path_cer', 'files.password']);
        }
        $regimenFiscal = DB::Table('regimenfiscal_catalogo')
            ->where('estado', 1)
            ->get();

        return view('facturacion.configuracion.emisor')->with(compact('emisor', 'regimenFiscal'));
    }
    /**
     * Vista editar clientes 
     */
    public function clientes(Request $request)
    {
        $filter = $request->query('filter');

        if (!empty($filter)) {
            $clientes =  DB::Table('clientes')
                ->where('nombreCliente', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $clientes =  Clientes::paginate(20);
        }
        //dd($clientes);
        return view('facturacion.configuracion.clientes')->with('clientes', $clientes)->with('filter', $filter);
    }
    /**Conceptos internos view loader */
    public function conceptosInternos(Request $request)
    {
        $claveProdServ =  DB::Table('claveproductoservicio_catalogo')
            ->where('estado', 1)
            ->get();
        $claveunidad =  DB::Table('claveunidad_catalogo')
            ->where('estado', 1)
            ->get();

        $filter = $request->query('filter');

        if (!empty($filter)) {
            $concepto_internos =  DB::Table('concepto_internos')
                ->where('descripcionConcepto', 'like', '%' . $filter . '%')
                ->paginate(20);
        } else {
            $concepto_internos =  DB::Table('concepto_internos')
                ->select('id', 'claveProductoServicio', 'descripcionConcepto', 'cuentasContables','claveUnidadFacturacion','numeroIdent')
                ->paginate(20);
        }
       // return view('facturacion.configuracion.formaPago')->with('formaPago', $formaPago)->with('filter', $filter);
        return view('facturacion.configuracion.conceptosInternos', [
            'claveProdServ' => $claveProdServ,
            'claveunidad' => $claveunidad,
            'concepto_internos'=> $concepto_internos,
            'filter'=> $filter
        ]);
    }
}
