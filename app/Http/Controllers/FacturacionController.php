<?php

namespace App\Http\Controllers;


use App\Models\Clientes;
use App\Models\ConceptoInterno;
use App\Models\Emisor;
use App\Models\Facturas;
use App\Models\File as Archivos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Codedge\Fpdf\Fpdf\Fpdf;
use \NumberFormatter;
use Illuminate\Support\Facades\Mail;

class PDF extends Fpdf
{
    // Page header
    public function Header()
    {
        // Logo
        $this->Image('storage/img/logo.png', 5, 10, 30);
        $this->SetFont('Arial', 'B', 13);
        // Move to the right
        $this->Cell(60);
        // Title

        // Line break
        $this->Ln(20);
    }

    // Page footer
    public function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Pagina ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}
class FacturacionController extends Controller
{
    public $pdf;
    public function __construct()
    {
        $this->middleware('auth');
        $this->pdf = new PDF;
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
       
    }
    /**
     * Timbrar factura
     */
    public function timbrarFactura(Request $request)
    {
        /**Getting special vars */
        $emisorDB = Emisor::where('rfc_emisor', $request->cuerpo['rfc_emisor'])->firstOrFail();
        $response = Http::get(env('URL_WEBSERVICE'), [
            'rfc' => $emisorDB->rfc_emisor,
            'fondo' => $request->cuerpo['fondo'],
            'id_proc' => "folio"
        ]);
        $folioResponse = $response->json();
        $serie = "";
        $complem = 0;
        if ($request->cuerpo['addendaType'] == 'donativos') {
            $serie = $request->cuerpo['fondo'] . "D";
        } else if ($request->cuerpo['addendaType'] == 'educativa') {
            $serie = $request->cuerpo['fondo'] . "C";
        } else {
            $serie = $request->cuerpo['fondo'] . "V";
        }

        /**XML creations */
        $document = new \DOMDocument('1.0', 'utf-8');
        $comprobante = $document->appendChild(
            $document->createElement('cfdi:Comprobante')
        );

        if ($request->cuerpo['addendaType'] == 'donativos') {
            $comprobante->setAttribute('xmlns:cfdi', "http://www.sat.gob.mx/cfd/4");
            $comprobante->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
            $comprobante->setAttribute('xsi:schemaLocation', "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/donat http://www.sat.gob.mx/sitio_internet/cfd/donat/donat11.xsd");
            $comprobante->setAttribute('xmlns:donat', "http://www.sat.gob.mx/donat");
        } else {
            $comprobante->setAttribute('xmlns:cfdi', "http://www.sat.gob.mx/cfd/4");
            $comprobante->setAttribute('xmlns:xsi', "http://www.w3.org/2001/XMLSchema-instance");
            $comprobante->setAttribute('xsi:schemaLocation', "http://www.sat.gob.mx/cfd/4 http://www.sat.gob.mx/sitio_internet/cfd/4/cfdv40.xsd http://www.sat.gob.mx/iedu http://www.sat.gob.mx/sitio_internet/cfd/iedu/iedu.xsd");
            $comprobante->setAttribute('xmlns:iedu', "http://www.sat.gob.mx/iedu");
        }

        $comprobante->setAttribute('Version', '4.0');
        $comprobante->setAttribute('Serie', $serie);
        $comprobante->setAttribute('Folio', $folioResponse['folio']);
        $comprobante->setAttribute('Fecha', $request->cuerpo['fechaTimbre']);
        $comprobante->setAttribute('Sello', ''); //PROCESS FROM OPENSSL
        $comprobante->setAttribute('FormaPago', $request->cuerpo['formaPago']);
        //$comprobante->setAttribute('NoCertificado', $emisorDB->numeroCertificado);
        $comprobante->setAttribute('NoCertificado', '30001000000400002449');
        $comprobante->setAttribute('Certificado', ''); //PROCESS FROM OPENSSL
        $comprobante->setAttribute('SubTotal', $request->cuerpo['subtotal']);
        $comprobante->setAttribute('Moneda', $request->cuerpo['monedaPago']);
        $comprobante->setAttribute('Total', $request->cuerpo['total']);
        $comprobante->setAttribute('TipoDeComprobante', $request->cuerpo['tipoComprobante']);
        $comprobante->setAttribute('Exportacion', '01');
        $comprobante->setAttribute('MetodoPago', $request->cuerpo['metodoPago']);
        $comprobante->setAttribute('LugarExpedicion', $request->cuerpo['c_postal']);
        $comprobante->setAttribute('Descuento', $request->cuerpo['descuentos']);

        if (strlen($request->cuerpo['rfcCliente']) < 11) {
            $request->cuerpo['rfcCliente'] =  'XAXX010101000';
            $request->cuerpo['nombreCliente'] = 'PUBLICO GENERAL';
        }
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
            $complem = 1;
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
        //$emisor->setAttribute('Nombre', $request->cuerpo['razonSocialEmisor']);
        //$emisor->setAttribute('Rfc', $request->cuerpo['rfc_emisor']);
        $emisor->setAttribute('Nombre', 'LUIS IAN ÑUZCO');
        $emisor->setAttribute('Rfc', 'IAÑL750210963');
        $emisor->setAttribute('RegimenFiscal', '616');
        //$emisor->setAttribute('RegimenFiscal', $request->cuerpo['regimen_emisor']);

        $receptor = $comprobante->appendChild(
            $document->createElement('cfdi:Receptor')
        );

        //$receptor->setAttribute('Rfc', strtoupper($request->cuerpo['rfcCliente']));
        $receptor->setAttribute('Rfc', 'ZALB930802QD2');
        //$receptor->setAttribute('Nombre', $request->cuerpo['nombreCliente']);
        $receptor->setAttribute('Nombre', 'BENJAMIN ZAVALA LEDESMA');
        //$receptor->setAttribute('DomicilioFiscalReceptor', $request->cuerpo['DomicilioFiscalReceptor']);
        $receptor->setAttribute('DomicilioFiscalReceptor', '67515');
        $receptor->setAttribute('RegimenFiscalReceptor', '616'); //regimen fiscal del receptor
        //$receptor->setAttribute('UsoCFDI', $request->cuerpo['usoCfdiCliente']);
        $receptor->setAttribute('UsoCFDI', 'S01');


        $conceptos = $comprobante->appendChild(
            $document->createElement('cfdi:Conceptos')
        );

        foreach ($request->cuerpo['conceptos'] as $concepto) {
            $conceptoXml = $document->createElement('cfdi:Concepto');
            $conceptoXml->setAttribute('ClaveProdServ', $concepto['ClaveProdServ']);
            $conceptoXml->setAttribute('NoIdentificacion', $concepto['NoIdentificacion']);
            $conceptoXml->setAttribute('Cantidad', $concepto['Cantidad']);
            $conceptoXml->setAttribute('ClaveUnidad', $concepto['ClaveUnidad']);
            $conceptoXml->setAttribute('Descripcion', $concepto['Descripcion']);
            $conceptoXml->setAttribute('ValorUnitario', $concepto['ValorUnitario']);
            $conceptoXml->setAttribute('Importe', $concepto['Importe']);
            $conceptoXml->setAttribute('Descuento', $concepto['Descuento']);
            $conceptoXml->setAttribute("ObjetoImp", $request->cuerpo['objetoImpuestos']);
            if ($request->cuerpo['addendaType'] == 'educativa') {
                foreach ($request->cuerpo['addenda'] as $element) {
                    if (strlen($element['rfcPago']) < 11) {
                        $element['rfcPago'] =  'XAXX010101000';
                        $element['nombreAlumno'] = 'PUBLICO GENERAL';
                    }
                    $complemento = $conceptoXml->appendChild(
                        $document->createElement('cfdi:ComplementoConcepto')
                    );
                    $educativa = $complemento->appendChild(
                        $document->createElement('iedu:instEducativas')
                    );
                    $educativa->setAttribute('CURP', $element['CURP']);
                    $educativa->setAttribute('autRVOE', $element['autRvoe']);
                    $educativa->setAttribute('nivelEducativo', $element['nivelEducativo']);
                    $educativa->setAttribute('nombreAlumno', $element['nombreAlumno']);
                    $educativa->setAttribute('rfcPago', $element['rfcPago']);
                    $educativa->setAttribute('version', '1.0');
                }
            }

            $conceptos->appendChild($conceptoXml);
        }

        if ($request->cuerpo['objetoImpuestos'] == "02") {
            //global impuesto node
            $impuestosTotal = $comprobante->appendChild(
                $document->createElement('cfdi:Impuestos')
            );
            $traslados = $impuestosTotal->appendChild(
                $document->createElement('cfdi:Traslados')
            );
            $traslado = $traslados->appendChild(
                $document->createElement('cfdi:Traslado')
            );
            $traslado->setAttribute('Impuesto', '002');
            $traslado->setAttribute('TipoFactor', 'Tasa');
            $traslado->setAttribute('TasaOCuota', '0.000000');
            $traslado->setAttribute('Importe', '0.00');
        }
        if ($request->cuerpo['addendaType'] == 'donativos') {

            $complemento = $comprobante->appendChild(
                $document->createElement('cfdi:Complemento')
            );
            $donataria = $complemento->appendChild(
                $document->createElement('donat:Donatarias')
            );

            $donataria->setAttribute('version','1.1');
            $donataria->setAttribute('noAutorizacion', $emisorDB->permisoDonataria);
            $donataria->setAttribute('fechaAutorizacion', $emisorDB->fechaDonataria);
            $donataria->setAttribute('leyenda', $emisorDB->leyendaDonataria);
        }


        $document->appendChild($comprobante);
        $xml_data = ($document->saveXML()); //DOCUMENTO XML PRESELLADO
        //postear al pac para sello y timbre
        $jsonXml = json_encode(array("data" => $xml_data)); // convert the XML string to JSON */
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, env('URL_FACTURACION'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonXml);

        $headers = array();
        $headers[] = 'Authorization:T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRUdkxWNXhiMGlma1FNSjJnUjJUWjgrOGRVMUFJZ3Jsak9vaGNmRUVDcDM0L1AyYkVyakpoVUtHNXNWT3UrUFlNaWJ3ZTJlekFqZFlncnpLVFpwTGpGTXkyRkNqL2xNOTYvQy9CSjJFWkh5a1A3ZklBemtXbFRoTU1WY0JBZThjOEFEMWhMWlZZODVMMHZYTGtRWnhZZzhUZWZOUE1lK29wb0k4RDlLVFNSV0RPTHp5blIvWjhzS2ZRQXJONmhQTGJYMWE1ajFOcUhGS29sdjgvdHZnRzhEOVFHLzRWejQ1TEZKY3FRNGUxSm5xMUtVbndJRU02a0ZyM2gyZG9hUitjd0gwb3cwbEZZbjdYMGYvQmpyTFdDeEJmNXAzb0REYUNLSTBXS3NWUkE1RXRqUlJDM0pyVXo0RnVzRTVjdGxtdGM5MFBncTliU3RJbWE3dUg0Yk90amNBanAzM2xGR3UydlB0YVpuUDZhdktCMTNjc2lmNHhZMzJ5alNqajl5VXE.oT6Yi6P2eAZHwmnj-HtUeRRLj7LZlSDne4i0q2bOOys';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }

        curl_close($ch);
        $result = json_decode($result, true);

        if ($result['status'] == 'error') {
            return json_encode(array('response' => $result, 'xml' => $xml_data));
        }
        $uuid = $result['data']['uuid'];
        $cfdi = $result['data']['cfdi'];
        $cadena = $result['data']['cadenaOriginalSAT'];

        /**CREAR XML */
        Storage::disk('xml')->put($uuid . '.xml', $cfdi);
        $dir = Http::get(env('URL_WEBSERVICE'), [
            'fondo' => $request->cuerpo['fondo'],
            'bunit' => $request->cuerpo['bunit'],
            'id_proc' => "direccion"
        ]);
        $direccionResponse = $dir->json();
        $direccion = $direccionResponse['direccion'];
        /**CREAR PDF */
        $tipoFactura = $request->cuerpo['addendaType'];
        $this->pdf = $this->processXmlForPdfIEDU($cfdi, $complem, $cadena, $direccion, $tipoFactura);
        $pdf = $this->pdf->Output('S');
        Storage::disk('pdf')->put($uuid . '.pdf', $pdf);
        $urlXml = asset('xml/' . $uuid . '.xml');
        $urlPdf = asset('pdf/' . $uuid . '.pdf');

        $pdfpath = public_path('pdf/' . $uuid . '.pdf');
        $data = [
            'cliente' => $request->cuerpo['razonCliente'],
            'urlfile' => $pdfpath,
            'filename' => $uuid,
            'data' => 'Hola a continuación encontraras tu factura añadida a este correo.'
        ];
       
        Mail::to($request->cuerpo['emailCliente'])->send(new \App\Mail\FacturaEmail($data));

        Facturas::updateOrCreate(
            ['uuid' => $result['data']['uuid']],
            [
                'uuid' => $result['data']['uuid'],
                'cadenaOriginalSAT' => $result['data']['cadenaOriginalSAT'],
                'fechaTimbrado' => $result['data']['fechaTimbrado'],
                'noCertificadoCFDI' => $result['data']['noCertificadoCFDI'],
                'noCertificadoSAT' => $result['data']['noCertificadoSAT'],
                'qrCode' => $result['data']['qrCode'],
                'selloCFDI' => $result['data']['selloCFDI'],
                'selloSAT' => $result['data']['selloSAT'],
                'cfdi' => $result['data']['cfdi'],
                'bunit' => $request->cuerpo['bunit'],
                'fondo' => $request->cuerpo['fondo'],
                'filepath' => $urlXml
            ]
        );

        return json_encode(array("xml" => $urlXml, "pdf" => $urlPdf), JSON_UNESCAPED_SLASHES);
    }
    /**
     * PROCCESS XML de factura PARA GENERAR PDF
     */
    public function processXmlForPdfIEDU($string, $complem, $cadena, $direccion, $tipoFactura)
    {
        $xml = simplexml_load_string($string);
        $ns = $xml->getNamespaces(true);
        $xml->registerXPathNamespace('c', $ns['cfdi']);
        $xml->registerXPathNamespace('t', $ns['tfd']);
        $versioncomplem = '';
        $noAutorizacioncomplem = '';
        $fechaAutorizacioncomplem = '';
        $leyenda = '';
        if ($tipoFactura == "educativa") {
            $xml->registerXPathNamespace('i', $ns['iedu']);
        } else if ($tipoFactura == 'donativos') {
            $xml->registerXPathNamespace('d', $ns['donat']);
            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Complemento//donat:Donatarias') as $Concepto) {
                $versioncomplem = $Concepto['version'];
                $noAutorizacioncomplem = $Concepto['noAutorizacion'];
                $fechaAutorizacioncomplem = $Concepto['fechaAutorizacion'];
                $leyenda = $Concepto['leyenda'];
            }
        }
        
        foreach ($xml->xpath('//cfdi:Comprobante') as $cfdiComprobante) {
            $version = $cfdiComprobante['Version'];
            $fecha = $cfdiComprobante['Fecha'];
            $sello = $cfdiComprobante['Sello'];
            $total = $cfdiComprobante['Total'];
            $subtotal = $cfdiComprobante['SubTotal'];
            $certificado = $cfdiComprobante['Certificado'];
            $formaPago = $cfdiComprobante['FormaPago'];
            $noCert = $cfdiComprobante['NoCertificado'];
            $tipoComp = $cfdiComprobante['TipoDeComprobante'];
            $lugar = $cfdiComprobante['LugarExpedicion'];
            $metodoPago = $cfdiComprobante['MetodoPago'];
            $folio = $cfdiComprobante['Folio'];
            $serie = $cfdiComprobante['Serie'];
            $moneda = $cfdiComprobante['Moneda'];
        }

        if ($formaPago == "01") {
            $formaPago = "01-Efectivo";
        } else if ($formaPago == "02") {
            $formaPago = "02-Cheque Nominativo";
        } else if ($formaPago == "03") {
            $formaPago = "03-Transferencia de fondos";
        } else if ($formaPago == "04") {
            $formaPago = "04-Tarjeta de crédito";
        } else {
            $formaPago = "28-Tarjeta de débito";
        }
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Emisor') as $Emisor) {
            $rfcemisor = $Emisor['Rfc'];
            $nomEmisor = $Emisor['Nombre'];
            $regFiscEmisor = $Emisor['RegimenFiscal'];
        }

        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Receptor') as $Receptor) {
            $rfcReceptor = $Receptor['Rfc'];
            $recNombre = $Receptor['Nombre'];
            $recUso = $Receptor['UsoCFDI'];
            $domFiscal = $Receptor['DomicilioFiscalReceptor'];
            $regFiscal = $Receptor['RegimenFiscalReceptor'];
        }

        foreach ($xml->xpath('//t:TimbreFiscalDigital') as $tfd) {
            $selloCFD = $tfd['SelloCFD'];
            $selloSAT = $tfd['SelloSAT'];
            $fechaTimbre = $tfd['FechaTimbrado'];
            $uuid = $tfd['UUID'];
            $certificadoSAT = $tfd['NoCertificadoSAT'];
            $versionTimbre = $tfd['Version'];
            $sellosat = $tfd['SelloSAT'];
            $rfcProvCertif = $tfd['RfcProvCertif'];
        }
        $uuidRelacionado = "";
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:CfdiRelacionados//cfdi:CfdiRelacionado') as $Concepto) {
            $uuidRelacionado = $Concepto['UUID'];
        }
        $tipoRelacion = "";
        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:CfdiRelacionados') as $Concepto) {
            $tipoRelacion = $Concepto['TipoRelacion'];
        }

        $telefono = "";
        $web = "";
        $correoCFDI = "";
        if ($rfcemisor == "FEB000705CA2") {
            //$direccion = "Lomitas de Cuchuma #1535, CP.21440 Tecate BC";
            $telefono = "646 153 5050";
            $web = "www.seaumn.org";
            $correoCFDI = "febcmatriz@gmail.com";
        } else if ($rfcemisor == "FEN00092718A") {
            //$direccion = "Noreste";
            $telefono = "646 153 5050";
            $web = "www.seaumn.org";
        } else if ($rfcemisor == "FEG000621ID6") {
            //$direccion = "Golfo";
            $telefono = "646 153 5050";
            $web = "www.seaumn.org";
        } else if ($rfcemisor == "SES130418QM4") {
            //$direccion = "Cap Alonso de Leon S/N";
            $telefono = "826 106 0174";
            $web = "www.seaumn.org";
        } else if ($rfcemisor == "FES020502DB1") {
            //$direccion = "C. RIO HUMAYA #284 PTE. COL. GUADALUPE C.P. 80220 CULIACAN SIN.";
            $telefono = "8186604773";
            $web = "www.seaumn.org";
            $correoCFDI = "fesac@gmail.com";
        } else if ($rfcemisor == "FEO000726P45") {
            //$direccion = "C. RIO HUMAYA #284 PTE. COL. GUADALUPE C.P. 80220 CULIACAN SIN.";
            $telefono = "8186604773";
            $web = "www.seaumn.org";
            $correoCFDI = "fesinac@gmail.com";
        } else if ($rfcemisor == "FEN000707QDA") {
            //$direccion = "C. RIO HUMAYA #284 PTE. COL. GUADALUPE C.P. 80220 CULIACAN SIN.";
            $telefono = "8186604773";
            $web = "www.seaumn.org";
            $correoCFDI = "fenacchihuahua@gmail.com";
        } else if ($rfcemisor == "FEN000814AC9") {
            //$direccion = "C. RIO HUMAYA #284 PTE. COL. GUADALUPE C.P. 80220 CULIACAN SIN.";
            $telefono = "8186604773";
            $web = "www.seaumn.org";
            $correoCFDI = "fenacsonora@gmail.com";
        }

        $attachment = $this->crearPdf(
            $sello,
            $total,
            $subtotal,
            $formaPago,
            $noCert,
            $tipoComp,
            $lugar,
            $metodoPago,
            $folio,
            $serie,
            $moneda,
            $rfcemisor,
            $nomEmisor,
            $regFiscEmisor,
            $rfcReceptor,
            $recNombre,
            $recUso,
            $domFiscal,
            $regFiscal,
            $versionTimbre,
            $selloCFD,
            $selloSAT,
            $fechaTimbre,
            $uuid,
            $certificadoSAT,
            $sellosat,
            $rfcProvCertif,
            $telefono,
            $web,
            $correoCFDI,
            $uuidRelacionado,
            $tipoRelacion,
            $xml,
            $complem,
            $cadena,
            $direccion,
            $tipoFactura,
            $versioncomplem,
            $noAutorizacioncomplem,
            $fechaAutorizacioncomplem,
            $leyenda
        );



        return $attachment;
    }
    /**
     * Generar PDF
     */
    public function crearPdf(
        $sello,
        $total,
        $subtotal,
        $formaPago,
        $noCert,
        $tipoComp,
        $lugar,
        $metodoPago,
        $folio,
        $serie,
        $moneda,
        $rfcemisor,
        $nomEmisor,
        $regFiscEmisor,
        $rfcReceptor,
        $recNombre,
        $recUso,
        $domFiscal,
        $regFiscal,
        $versionTimbre,
        $selloCFD,
        $selloSAT,
        $fechaTimbre,
        $uuid,
        $certificadoSAT,
        $sellosat,
        $rfcProvCertif,
        $telefono,
        $web,
        $correoCFDI,
        $uuidRelacionado,
        $tipoRelacion,
        $xml,
        $complem,
        $cadena,
        $direccion,
        $tipoFactura,
        $versioncomplem,
        $noAutorizacioncomplem,
        $fechaAutorizacioncomplem,
        $leyenda
    ) {

        
        $this->pdf->AddPage();
        $this->pdf->AliasNbPages();
        $this->pdf->SetFont('Arial', 'B', 10);
        $this->pdf->SetXY(60.0, 15.0);
        $this->pdf->Cell(80, 4, iconv('UTF-8', 'iso-8859-1', $rfcemisor), 0, 1, 'C');
        $this->pdf->SetXY(60.0, 19.0);
        $this->pdf->Cell(80, 4, iconv('UTF-8', 'iso-8859-1', $nomEmisor), 0, 1, 'C');
        $this->pdf->SetXY(60.0, 23.0);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(80, 4, iconv('UTF-8', 'iso-8859-1', $direccion), 0, 1, 'C'); //get direccion from DB o webService
        $this->pdf->SetXY(60.0, 27.0);
        $this->pdf->Cell(80, 4, $telefono, 0, 1, 'C');
        $this->pdf->SetXY(60.0, 31.0);
        $this->pdf->Cell(80, 4, $web, 0, 1, 'C');
        $this->pdf->SetXY(60.0, 35.0);
        $this->pdf->Cell(80, 4, $correoCFDI, 0, 1, 'C');

        $this->pdf->SetXY(10.0, 45.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(30, 4, 'UUID', 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(40.0, 45.0);
        $this->pdf->Cell(80, 4, $uuid, 1, 1, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(10.0, 49.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(30, 4, iconv('UTF-8', 'iso-8859-1', 'Fecha emisión'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(40.0, 49.0);
        $this->pdf->Cell(80, 4, $fechaTimbre, 1, 1, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(10.0, 53.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(30, 4, iconv('UTF-8', 'iso-8859-1', 'Lugar expedición'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(40.0, 53.0);
        $this->pdf->Cell(80, 4, $lugar, 1, 1, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(10.0, 57.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(30, 4, iconv('UTF-8', 'iso-8859-1', 'Metodo de pago'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(40.0, 57.0);
        $this->pdf->Cell(80, 4, $metodoPago, 1, 1, 'L'); //subcolumna izquierda

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(120.0, 45.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(35, 4, iconv('UTF-8', 'iso-8859-1', 'Folio expedición'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(155.0, 45.0);
        $this->pdf->Cell(40, 4, $folio, 1, 1, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(120.0, 49.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(35, 4, iconv('UTF-8', 'iso-8859-1', 'Forma de pago'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(155.0, 49.0);
        $this->pdf->Cell(40, 4, iconv('UTF-8', 'iso-8859-1', $formaPago), 1, 1, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(120.0, 53.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(35, 4, iconv('UTF-8', 'iso-8859-1', 'Tipo de Comprobante'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(155.0, 53.0);
        $this->pdf->Cell(40, 4, $tipoComp, 1, 1, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(120.0, 57.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(35, 4, iconv('UTF-8', 'iso-8859-1', 'Serie'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(155.0, 57.0);
        $this->pdf->Cell(40, 4, $serie, 1, 1, 'L');
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(120.0, 61.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(35, 4, iconv('UTF-8', 'iso-8859-1', 'Moneda'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(155.0, 61.0);
        $this->pdf->Cell(40, 4, $moneda, 1, 1, 'L'); //ACA TERMINA LA PRIMER LINA DE LA FACTURA

        $this->pdf->SetFont('Arial', 'B', 8); //EMISOR 2.0
        $this->pdf->SetXY(10.0, 67.0);
        $this->pdf->Cell(30, 4, iconv('UTF-8', 'iso-8859-1', 'EMISOR'), 0, 1, 'L', 0);

        $this->pdf->SetFont('Arial', '', 7);
        $this->pdf->SetXY(35.0, 73.0);
        $this->pdf->SetFillColor(255, 255, 255); //grey color
        $this->pdf->MultiCell(70, 4, iconv('UTF-8', 'iso-8859-1', $nomEmisor), 1, 1, 'L', 0);
        $ynueva1 = $this->pdf->GetY();
        $this->pdf->SetX(35);
        $this->pdf->Cell(70, 4, iconv('UTF-8', 'iso-8859-1', $rfcemisor), 1, 2, 'L');
        $this->pdf->Cell(70, 4, iconv('UTF-8', 'iso-8859-1', $regFiscEmisor), 1, 2, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(10.0, 73.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'Razón Social'), 1, 1, 'L', 1);
        $this->pdf->SetXY(10, $ynueva1);
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'RFC'), 1, 1, 'L', 1);
        $this->pdf->SetXY(10, $ynueva1 + 4);
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'Regimen Fiscal'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8); //RECEPTOR
        $this->pdf->SetXY(108.0, 67.0);
        $this->pdf->Cell(30, 4, iconv('UTF-8', 'iso-8859-1', 'Receptor'), 0, 1, 'L', 0);

        $this->pdf->SetFont('Arial', '', 7);
        $this->pdf->SetXY(133.0, 73.0);
        $this->pdf->SetFillColor(255, 255, 255); //grey color
        $this->pdf->MultiCell(62, 4, iconv('UTF-8', 'iso-8859-1', $recNombre), 1, 1, 'L', 0);
        $ynueva2 = $this->pdf->GetY();
        $this->pdf->SetX(133);
        $this->pdf->Cell(62, 4, $rfcReceptor, 1, 2, 'L');
        $this->pdf->Cell(62, 4, $recUso, 1, 2, 'L');
        $this->pdf->Cell(62, 4, $domFiscal, 1, 2, 'L');
        $this->pdf->Cell(62, 4, $regFiscal, 1, 2, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(108.0, 73.0);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'Razón Social'), 1, 1, 'L', 1);
        $this->pdf->SetXY(108, $ynueva2);
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'RFC'), 1, 1, 'L', 1);
        $this->pdf->SetXY(108, $ynueva2 + 4);
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'Uso CFDI'), 1, 1, 'L', 1);
        $this->pdf->SetXY(108, $ynueva2 + 8);
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'Domicilio Fiscal'), 1, 1, 'L', 1);
        $this->pdf->SetXY(108, $ynueva2 + 12);
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'Regimen Fiscal'), 1, 1, 'L', 1);



        $this->pdf->SetFont('Arial', 'B', 8); //Conceptos
        $this->pdf->SetXY(10.0, 88.00);
        $this->pdf->Cell(30, 4, iconv('UTF-8', 'iso-8859-1', 'Conceptos'), 0, 1, 'L', 0);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(10.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(20, 4, iconv('UTF-8', 'iso-8859-1', '#'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(15.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(15, 4, iconv('UTF-8', 'iso-8859-1', 'Cantidad'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(30.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(20, 4, iconv('UTF-8', 'iso-8859-1', 'ClavProdServ'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(50.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(20, 4, iconv('UTF-8', 'iso-8859-1', 'ClaveUnidad'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(70.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(55, 4, iconv('UTF-8', 'iso-8859-1', 'Descripción'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(125.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(25, 4, iconv('UTF-8', 'iso-8859-1', 'NoIdentificacion'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(150.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(20, 4, iconv('UTF-8', 'iso-8859-1', 'Valor unitario'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(170.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(15, 4, iconv('UTF-8', 'iso-8859-1', ' Importe'), 1, 1, 'L', 1);

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(170.0, 94);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(15, 4, iconv('UTF-8', 'iso-8859-1', ' Importe'), 1, 1, 'L', 1);

        $x = 10.00;
        $y = 98.00;
        $importes = array();
        $in1 = 1;

        foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto') as $Concepto) {
            $conceptoFact = $Concepto['Unidad'];
            $conceptoImporte = $Concepto['Importe'];
            $conceptoCantidad = $Concepto['Cantidad'];
            $conceptoDescr = $Concepto['Descripcion'];
            $conceptoValor = $Concepto['ValorUnitario'];
            $claveProdServ = $Concepto['ClaveProdServ'];
            $claveUnidad = $Concepto['ClaveUnidad'];
            $NoIdentificacion = $Concepto['NoIdentificacion'];
            $this->pdf->SetFont('Arial', '', 7);
            $this->pdf->SetXY($x, $y);
            $this->pdf->Cell(5, 4, $in1, 1, 1, 'L');

            $this->pdf->SetXY($x + 5, $y);
            $this->pdf->Cell(15, 4, $conceptoCantidad, 1, 1, 'L');

            $this->pdf->SetXY($x + 20, $y);
            $this->pdf->Cell(20, 4, $claveProdServ, 1, 1, 'L');

            $this->pdf->SetXY($x + 40, $y);
            $this->pdf->Cell(20, 4, $claveUnidad, 1, 1, 'L');

            $this->pdf->SetXY($x + 60, $y);
            $this->pdf->Cell(55, 4, iconv('UTF-8', 'iso-8859-1', $conceptoDescr), 1, 1, 'L');

            $this->pdf->SetXY($x + 115, $y);
            $this->pdf->Cell(25, 4, $NoIdentificacion, 1, 1, 'L');

            $this->pdf->SetXY($x + 140, $y);
            $this->pdf->Cell(20, 4, '$' . $conceptoValor, 1, 1, 'L');

            $this->pdf->SetXY($x + 160, $y);
            $this->pdf->Cell(15, 4, '$' . $conceptoImporte, 1, 1, 'L');

            $y = $y + 4;
            $in1++;
        }

        $this->pdf->SetFont('Arial', '', 8);
        if ($tipoFactura == 'educativa') {
            $y = $y + 5;

            $this->pdf->SetFont('Arial', 'B', 8); //Addendas
            $this->pdf->SetXY(10.0, $y);
            $this->pdf->Cell(30, 4, iconv('UTF-8', 'iso-8859-1', 'Addenda'), 0, 1, 'L', 0);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->SetXY(10.0, $y + 5);
            $this->pdf->SetFillColor(211, 211, 211); //grey color
            $this->pdf->Cell(10, 4, iconv('UTF-8', 'iso-8859-1', '#'), 1, 1, 'L', 1);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->SetXY(15.0, $y + 5);
            $this->pdf->SetFillColor(211, 211, 211); //grey color
            $this->pdf->Cell(35, 4, iconv('UTF-8', 'iso-8859-1', 'CURP'), 1, 1, 'L', 1);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->SetXY(50.0, $y + 5);
            $this->pdf->SetFillColor(211, 211, 211); //grey color
            $this->pdf->Cell(20, 4, iconv('UTF-8', 'iso-8859-1', 'RVOE'), 1, 1, 'L', 1);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->SetXY(70.0, $y + 5);
            $this->pdf->SetFillColor(211, 211, 211); //grey color
            $this->pdf->Cell(40, 4, iconv('UTF-8', 'iso-8859-1', 'Nivel Educativo'), 1, 1, 'L', 1);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->SetXY(110.0, $y + 5);
            $this->pdf->SetFillColor(211, 211, 211); //grey color
            $this->pdf->Cell(60, 4, iconv('UTF-8', 'iso-8859-1', 'Nombre'), 1, 1, 'L', 1);

            $this->pdf->SetFont('Arial', 'B', 8);
            $this->pdf->SetXY(170.0, $y + 5);
            $this->pdf->SetFillColor(211, 211, 211); //grey color
            $this->pdf->Cell(23, 4, iconv('UTF-8', 'iso-8859-1', 'RFC'), 1, 1, 'L', 1);
            $y = $y + 9;
            $this->pdf->SetFont('Arial', '', 7);
            $in2 = 1;

            foreach ($xml->xpath('//cfdi:Comprobante//cfdi:Conceptos//cfdi:Concepto//cfdi:ComplementoConcepto//iedu:instEducativas') as $Concepto) {
                $curp = $Concepto['CURP'];
                $rvoe = $Concepto['autRVOE'];
                $nivel = $Concepto['nivelEducativo'];
                $nombreAlumn = $Concepto['nombreAlumno'];
                $rfcPago = $Concepto['rfcPago'];
                $version = $Concepto['version'];
                $this->pdf->SetXY(10, $y);
                $this->pdf->Cell(5, 4, $in2, 1, 1, 'L');

                $this->pdf->SetXY(15.0, $y);
                $this->pdf->Cell(35, 4, $curp, 1, 1, 'L');

                $this->pdf->SetXY(50.0, $y);
                $this->pdf->Cell(20, 4, $rvoe, 1, 1, 'L');

                $this->pdf->SetXY(70.0, $y);
                $this->pdf->Cell(40, 4, $nivel, 1, 1, 'L');

                $this->pdf->SetXY(110.0, $y);
                $this->pdf->Cell(60, 4, iconv('UTF-8', 'iso-8859-1', $nombreAlumn), 1, 1, 'L');

                $this->pdf->SetXY(170.0, $y);
                $this->pdf->Cell(23, 4, $rfcPago, 1, 1, 'L');

                $in2++;
                $y = $y + 4;
            }
        } else if ($tipoFactura == 'donativos') {
            $y = $y + 5;
            $addDonat = $versioncomplem."|" . $noAutorizacioncomplem . "|" . $fechaAutorizacioncomplem . "|".$leyenda."||"; 

            $this->pdf->SetFont('Arial', 'B', 8); //Addendas
            $this->pdf->SetXY(10.0, $y);
            $this->pdf->Cell(30, 4, iconv('UTF-8', 'iso-8859-1', 'Addenda'), 0, 1, 'L', 0);
            $this->pdf->SetFont('Arial', '', 6);
            $this->pdf->SetXY(10.0, $y + 4);
            $this->pdf->MultiCell(190, 4, iconv('UTF-8', 'iso-8859-1', $addDonat), 0, 1, 'L', 1);
            $y = $y + 14;
        }


        $totalRec1 = number_format((float) $total, 2);
        $n = strval($total);
        $whole = explode('.', $n); // 1
        $f = new NumberFormatter("es", NumberFormatter::SPELLOUT);
        error_log($total);
        $cantLetras = $f->format(floatval($total));

        if (strpos($cantLetras, 'coma') !== false) {
            $cantLetras = substr($cantLetras, 0, strpos($cantLetras, "coma"));
        }

        $cantLetras1 = $cantLetras . " " . $whole[1] . "/100" . " MN";

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(110.0, $y + 10);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(20, 4, iconv('UTF-8', 'iso-8859-1', 'SubTotal'), 1, 1, 'L', 1);
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(130.0, $y + 10);
        $this->pdf->Cell(65, 4, '$' . number_format((float) $subtotal, 2), 1, 1, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(110.0, $y + 14);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(20, 4, iconv('UTF-8', 'iso-8859-1', 'IVA'), 1, 1, 'L', 1);
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(130.0, $y + 14);
        $this->pdf->Cell(65, 4, '$0.0', 1, 1, 'L');

        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->SetXY(110.0, $y + 18);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(20, 4, iconv('UTF-8', 'iso-8859-1', 'Total'), 1, 1, 'L', 1);
        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(130.0, $y + 18);
        $this->pdf->Cell(65, 4, '$' . $totalRec1, 1, 1, 'L');

        $this->pdf->SetFont('Arial', '', 8);
        $this->pdf->SetXY(110.0, $y + 22);
        $this->pdf->SetFillColor(211, 211, 211); //grey color
        $this->pdf->Cell(85, 4, iconv('UTF-8', 'iso-8859-1', $cantLetras1), 1, 1, 'C', 1);

        $re = $rfcemisor;
        $rr = $rfcReceptor;
        $sellosub = substr($sello, -8);

        $data = urlencode('https://verificacfdi.facturaelectronica.sat.gob.mx/default.aspx?re=' . $re . '&rr=' . $rr . '&tt=' . $totalRec1 . '&id=' . $uuid . "&fe=" . $sellosub);
        $this->pdf->Image(env('URL_QR') . $data, 5, $y + 5, 40, 40, "png");



        if ($complem == 1) {
            $this->pdf->SetXY(45, $y + 22.5);
            $this->pdf->SetFillColor(211, 211, 211); //grey color
            $this->pdf->Cell(60, 4, iconv('UTF-8', 'iso-8859-1', 'CFDI Relacionado'), 1, 1, 'L', 1);
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->SetXY(45.0, $y + 26.5);
            $this->pdf->Cell(40, 4, $uuidRelacionado, 0, 1, 'L');
            $this->pdf->SetXY(45, $y + 30.5);
            $this->pdf->SetFillColor(211, 211, 211); //grey color
            $this->pdf->Cell(60, 4, iconv('UTF-8', 'iso-8859-1', 'Tipo de relación'), 1, 1, 'L', 1);
            $this->pdf->SetFont('Arial', '', 8);
            $this->pdf->SetXY(45.0, $y + 34.5);
            $this->pdf->Cell(40, 4, iconv('UTF-8', 'iso-8859-1', $tipoRelacion), 0, 1, 'L');
        }

        if ($y + 45 > 196) { //FOOTER DE FACTURA
            $this->pdf->AddPage();
            $this->pdf->AliasNbPages();
            $y = 0;
        }

        $this->pdf->SetXY(10.0, $y + 45);
        $this->pdf->SetFillColor(211, 211, 211);
        $this->pdf->MultiCell(190, 4, iconv('UTF-8', 'iso-8859-1', $selloSAT), 0, 0, 'L');

        $this->pdf->SetXY(10.0, $y + 65);
        $this->pdf->MultiCell(190, 4, "SelloCFD=" . $selloCFD, 0, 0, 'L');

        $this->pdf->SetXY(10.0, $y + 85);
        $this->pdf->MultiCell(190, 4, "No Certificado=" . $certificadoSAT, 0, 0, 'L');

        $this->pdf->SetXY(10.0, $y + 95);
        $this->pdf->MultiCell(190, 4, "Fecha de timbrado=" . $fechaTimbre, 0, 0, 'L');

        $this->pdf->SetXY(10.0, $y + 105);
        $this->pdf->MultiCell(190, 4, "RFC del proveedor del certificado=" . $rfcProvCertif, 0, 0, 'L');

        $this->pdf->SetXY(10.0, $y + 115);
        $this->pdf->MultiCell(190, 4, iconv('UTF-8', 'iso-8859-1', "Cadena SAT=" . $cadena), 0, 0, 'L', 1);

        $this->pdf->SetXY(60.0, $y + 135);
        $this->pdf->SetFont('Arial', 'B', 8);
        $this->pdf->Cell(90, 4, iconv('UTF-8', 'iso-8859-1', "Este documento es una representación impresa de un CFDI versión 4.0"), 0, 0, 'C', 1);

        return $this->pdf;
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
                'permisoDonataria'       => $request->permisoDonataria,
                'numeroCertificado'       => $request->numeroCertificado,
                'nombreColegio'       => $request->nombreColegio
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
                        $pem = "-----BEGIN PRIVATE KEY-----\n$pem-----END PRIVATE KEY-----\n";
                        Storage::disk('tmp')->put($rfc . '.pem', $pem);
                    }
                    $continue = true;
                }
            }
            if ($continue) {
                $file = Archivos::updateOrCreate(
                    ['rfc' => $rfc],
                    ['name_key' => $keyfile_name, 'name_cer' => $certificate_name, 'path_cer' => $certificate_path, 'path_key' => $keyfile_path, 'password' => $password, 'extension' => $ext]
                );
                return back()->with('Success!', 'Certificado subido correctamente');
            } else {
                return back()->with('Warning', 'Archivos no validos');
            }
        }
        $file = Archivos::updateOrCreate(
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
        
        Clientes::updateOrCreate(
            ['emailCliente'  => $request->emailCliente],
            [
                'nombreCliente'          => $request->nombreCliente,
                'razonCliente'         => $request->razonCliente,
                'rfcCliente'       => $request->rfcCliente,
                'usoCfdiCliente'       => $request->usoCfdiCliente,
                'personaFisicaCliente'       => $request->personaFisicaCliente,
                'bunit'       => $bunit,
                'DomicilioFiscalReceptor'  => $request->DomicilioFiscalReceptor,
                'RegimenFiscalReceptor'  => $request->RegimenFiscalReceptor
            ]
        );
        return response()->json(['message' => 'Cliente guardado con éxito en ' . $bunit]);
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
    public function guardarConceptoInterno(Request $request)
    {
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

    /**
     * Obtener datos de addenda educativa
     */
    public function getAddendaEducativa(Request $request)
    {
        //OBTENER FONDO DE WS
        $user = Auth::user();
        $cliente = new Client();
        $response = $cliente->request('POST', env('URL_WEBSERVICE'), [
            'form_params' => [
                'bunit' => $user->bunit_account,
                'email' => $user->email,
                'alumnosbd' => 'AlumnosP',
                'fondo' => $request->fondo,
                'facturacionbd' => 'FacturacionP'
            ]
        ]);
        $data = json_decode($response->getBody()->getContents());
        return $data;
    }

}
