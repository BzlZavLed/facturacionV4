<?php

namespace App\Http\Controllers;


use App\Models\Clientes;
use App\Models\ConceptoInterno;
use App\Models\Emisor;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class FacturacionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function borrarCliente($id)
    {
        Clientes::where('id', $id)->delete();
        return redirect()->route('clientes');
    }
    /**
     * actualizar clientes en el menú de configuración
     */
    public function clientesUpdate(Request $request)
    {   
        //dd($request);
        if ($request->name == "emailCliente") {
            $validEmail = $request->validate([
                'value' => 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix'
            ]);
            if ($validEmail) {
                Clientes::where('id', $request->pk)->update([$request->name => $request->value]);
                return response()->json(['code' => 200], 200);
            } else {
                return "Invalid Email";
            }
        } else {
            Clientes::where('id', $request->pk)->update([$request->name => $request->value]);
            return response()->json(['code' => 200], 200);
        }
        return "Error ".$request;
    }
    /**
     * Timbrar factura WIP
     */
    public function timbrarFactura(Request $request)
    {
        $document = new \DOMDocument('1.0', 'utf-8');
        $comprobante = $document->appendChild(
            $document->createElement('cfdi:Comprobante')
        );

        $comprobante->setAttribute('xmlns:cfdi', "http://www.sat.gob.mx/cfd/4");
        $comprobante->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
        $comprobante->setAttribute('xsi:schemaLocation', "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/iedu http://www.sat.gob.mx/sitio_internet/cfd/iedu/iedu.xsd");
        $comprobante->setAttribute('xmlns:iedu', "http://www.sat.gob.mx/iedu");

        $comprobante->setAttribute('Version', '4.0');
        $comprobante->setAttribute('Serie', '');
        $comprobante->setAttribute('Folio', '');
        $comprobante->setAttribute('Fecha', $request->cuerpo['fechaTimbre']);
        $comprobante->setAttribute('Sello', '');
        $comprobante->setAttribute('FormaPago', $request->cuerpo['formaPago']);
        $comprobante->setAttribute('NoCertificado', '');
        $comprobante->setAttribute('Certificado', '');
        $comprobante->setAttribute('SubTotal', $request->cuerpo['subtotal']);
        $comprobante->setAttribute('Moneda', $request->cuerpo['monedaPago']);
        $comprobante->setAttribute('Total', '0');
        $comprobante->setAttribute('TipoDeComprobante', $request->cuerpo['tipoComprobante']);
        $comprobante->setAttribute('Exportacion', '01');
        $comprobante->setAttribute('MetodoPago', $request->cuerpo['metodoPago']);
        $comprobante->setAttribute('LugarExpedicion', $request->cuerpo['c_postal']);

        $publicogeneral = strcmp(strtoupper($request->cuerpo['rfcCliente']), "XAXX010101000");
        if ($publicogeneral == 0) {
            $month = date("m", strtotime($request->cuerpo['datetimepickerinput']));
            $year = date("Y", strtotime($request->cuerpo['datetimepickerinput']));

            $infoglobal = $comprobante->appendChild(
                $document->createElement('cfdi:InformacionGlobal')
            );
            $infoglobal->setAttribute('Meses', $month);
            $infoglobal->setAttribute('Año', $year);
            $infoglobal->setAttribute('Periodicidad', '04');
        }
        if ($request->cuerpo['cfdiRelacionado'] != "-") {
            $cancelacion = $comprobante->appendChild(
                $document->createElement('cfdi:CfdiRelacionados')
            );
            $cancelacion->setAttribute('TipoRelacion', $request->cuerpo['cfdiRelacionado']);
            $cfdiRelac = $document->createElement("cfdi:CfdiRelacionado");
            $cfdiRelac->setAttribute("UUID", $request->cuerpo['uuidRelacionado']);
            $cancelacion->appendChild($cfdiRelac);
        }

        $emisor = $comprobante->appendChild(
            $document->createElement('cfdi:Emisor')
        );
        $emisor->setAttribute('Nombre', $request->cuerpo['razonSocialEmisor']); 
        $emisor->setAttribute('Rfc', $request->cuerpo['rfc_emisor']); 
        $emisor->setAttribute('RegimenFiscal', $request->cuerpo['regimen_emisor']); 
    
        $receptor = $comprobante->appendChild(
            $document->createElement('cfdi:Receptor')
        );
    
        $receptor->setAttribute('Rfc', strtoupper($request->cuerpo['rfcCliente'])); 
        $receptor->setAttribute('Nombre', $request->cuerpo['nombreCliente']);
        $receptor->setAttribute('DomicilioFiscalReceptor', $request->cuerpo['DomicilioFiscalReceptor']);
        $receptor->setAttribute('RegimenFiscalReceptor', '616'); //regimen fiscal del receptor AGREGAR A CLIENTES
        $receptor->setAttribute('UsoCFDI', $request->cuerpo['usoCfdiCliente']);
        
        
        $conceptos = $comprobante->appendChild(
            $document->createElement('cfdi:Conceptos')
        );

        foreach($request->cuerpo['conceptos'] as $concepto) {
            $conceptoXml = $document->createElement('cfdi:Concepto');
            $conceptoXml->setAttribute('ClaveProdServ', $concepto['ClaveProdServ']);
            $conceptoXml->setAttribute('NoIdentificacion', $concepto['NoIdentificacion']);
            $conceptoXml->setAttribute('Cantidad', $concepto['Cantidad']);
            $conceptoXml->setAttribute('Unidad', $concepto['ClaveUnidad']);
            $conceptoXml->setAttribute('Descripcion', $concepto['Descripcion']);
            $conceptoXml->setAttribute('ValorUnitario', $concepto['ValorUnitario']);
            $conceptoXml->setAttribute('Importe', $concepto['Importe']);
            $conceptoXml->setAttribute('Descuento', $concepto['Descuento']);
            $conceptoXml->setAttribute("ObjetoImp", $concepto['ObjetoImpuestos']);
            $conceptos->appendChild($conceptoXml);
        }
        
       


        $document->appendChild($comprobante);

        $xml_data = ($document->saveXML());



        return $xml_data;
    }
    /**
     * guardar emisor en base de datos
     */
    public function guardarEmisor(Request $request)
    {
        Emisor::updateOrCreate(
            ['rfc_emisor' => $request->rfc_emisor],
            [
                'razon_emisor'  => $request->razon_emisor,
                'rfc_emisor'          => $request->rfc_emisor,
                'regimen_emisor'         => $request->regimen_emisor,
                'c_postal'       => $request->c_postal,
                'bunit' => $request->bunit,
                'email_emisor'       => $request->email_emisor,
                'zona'       => $request->zona,
                'versionDonataria'       => $request->versionDonataria,
                'leyendaDonataria'       => $request->leyendaDonataria,
                'fechaDonataria'       => $request->fechaDonataria,
                'permisoDonataria'       => $request->permisoDonataria
            ]
        );
        return redirect()->route('emisor');
    }
    /**
     * subir a servidor archivos de certificados
     */
    public function storeFiles(Request $request)
    {
        $password = $request->password;
        $rfc = $request->rfc;
        $accepted_extensions = ['cer', 'key'];
        $continue = false;
        $certificate_path = '';
        $keyfile_path = '';
        $certificate_name = '';
        $keyfile_name = '';
        if ($request->hasfile('certificates')) {
            foreach ($request->file('certificates') as $file) {
                $name = $file->getClientOriginalName();
                $extension = explode(".", $name);
                $ext = $extension[1];
                if (in_array($ext, $accepted_extensions)) {
                    if ($ext == 'cer') {
                        $certificate_name = $file->getClientOriginalName();
                        $certificate_path = $file->move(public_path('certificados'), $certificate_name);
                    }
                    if ($ext == 'key') {
                        $keyfile_name = $file->getClientOriginalName();
                        $keyfile_path = $file->move(public_path('certificados'), $keyfile_name);

                        $keyFileContent = file_get_contents(public_path('certificados') . "/" . $keyfile_name);
                        $pem = chunk_split(base64_encode($keyFileContent), 64, "\n");
                        $pem = "-----BEGIN PRIVATE KEY-----\n" . $pem . "-----END PRIVATE KEY-----\n";
                        Storage::disk('tmp')->put($rfc . '.pem', $pem);
                    }
                    $continue = true;
                }
            }
            if ($continue) {
                $file = File::updateOrCreate(
                    ['rfc' => $rfc],
                    ['name_key' => $keyfile_name, 'name_cer' => $certificate_name, 'path_cer' => $certificate_path, 'path_key' => $keyfile_path, 'password' => $password, 'extension' => $ext]
                );
                return back()->with('Success!', 'Certificado subido correctamente');
            } else {
                return back()->with('Warning', 'Archivos no validos');
            }
        }
        $file = File::updateOrCreate(
            ['rfc' => $rfc],
            ['password' => $password]
        );
        return back()->with('Success!', 'Password actualizado');
    }
    /**
     * Guardar cliente recibe request
     */
    public function guardarCliente(Request $request)
    {
        $user = Auth::user();
        $bunit = $user->bunit_account;
        $cliente = Clientes::where('emailCliente', '=', $request->emailCliente)->first();
        if ($cliente == null) {
            Clientes::create([
                'nombreCliente'          => $request->nombreCliente,
                'razonCliente'         => $request->razonCliente,
                'rfcCliente'       => $request->rfcCliente,
                'emailCliente' => $request->emailCliente,
                'usoCfdiCliente'       => $request->usoCfdiCliente,
                'personaFisicaCliente'       => $request->personaFisicaCliente,
                'bunit'       => $bunit,
                'DomicilioFiscalReceptor'  => $request->DomicilioFiscalReceptor
            ]);
            return response()->json(['message' => 'Cliente guardado con éxito ' . $bunit]);
        } else {
            return response()->json(['message' => 'Cliente ya existe']);
        }
    }
    /**
     * Actualizar clave de producto servicio para activar o desactivar
     */
    public function updateClaveProdServ($id, $value)
    {
        DB::table('claveproductoservicio_catalogo')
            ->where('id', $id)
            ->update(['estado' => $value]);
        return redirect()->route('claveProdServ');
    }
    /**
     * Actualizar updateClaveUnidad para activar o desactivar
     */
    public function updateClaveUnidad($id, $value)
    {
        DB::table('claveunidad_catalogo')
            ->where('id', $id)
            ->update(['estado' => $value]);
        return redirect()->route('claveUnidad');
    }
    /**
     * Actualizar updateFormaPago para activar o desactivar
     */
    public function updateFormaPago($id, $value)
    {
        DB::table('formapago_catalogo')
            ->where('id', $id)
            ->update(['estado' => $value]);
        return redirect()->route('formaPago');
    }
    /**
     * Actualizar updateMetodoPago para activar o desactivar
     */
    public function updateMetodoPago($id, $value)
    {
        DB::table('metodopago_catalogo')
            ->where('id', $id)
            ->update(['estado' => $value]);
        return redirect()->route('metodoPago');
    }
    /**
     * Actualizar updateTipoComprobante para activar o desactivar
     */
    public function updateTipoComprobante($id, $value)
    {
        DB::table('tipocomprobante_catalogo')
            ->where('id', $id)
            ->update(['estado' => $value]);
        return redirect()->route('tipoComprobante');
    }
    /**
     * Actualizar updateUsoCfdi para activar o desactivar
     */
    public function updateUsoCfdi($id, $value, $campo)
    {
        DB::table('usocfdi_catalogo')
            ->where('id', $id)
            ->update([$campo => $value]);
        return redirect()->route('usoCfdi');
    }
    /**
     * Actualizar updateTipoRelacion para activar o desactivar
     */
    public function updateTipoRelacion($id, $value)
    {
        DB::table('cfdirelacionados_catalogo')
            ->where('id', $id)
            ->update(['estado' => $value]);
        return redirect()->route('tipoRelacion');
    }
    /**
     * Actualizar updateObjetoImpuesto para activar o desactivar
     */
    public function updateObjetoImpuesto($id, $value)
    {
        DB::table('objetoimp_catalogo')
            ->where('id', $id)
            ->update(['estado' => $value]);
        return redirect()->route('objetoImpuesto');
    }
    /**Guardar concepto interno */
    public function guardarConceptoInterno(Request $request){
        ConceptoInterno::create(
            [
                'claveProductoServicio'  => $request->claveProductoServicio,
                'descripcionConcepto'          => $request->descripcionConcepto,
                'numeroIdent'          => $request->numeroIdent,
                'cuentasContables'         => $request->cuentasContables,
                'claveUnidadFacturacion'       => $request->claveUnidadFacturacion
                
            ]
        );
        return redirect()->route('conceptosInternos');
    }

    /**
     * Borrar deleteConceptoInterno
     */
    public function deleteConceptoInterno($id)
    {
        ConceptoInterno::destroy($id);
        return redirect()->route('conceptosInternos');
    }

    

}
