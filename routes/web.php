<?php
 
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginAuthController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\ViewsController; 
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
 
Route::get('dashboard', [LoginAuthController::class, 'dashboard']); 
Route::post('loginBlue', [LoginAuthController::class, 'loginBlue'])->name('loginBlue');
Route::get('/', [LoginAuthController::class, 'index'])->name('/');//ENTRY POINT
Route::post('custom-login', [LoginAuthController::class, 'customLogin'])->name('login.custom'); //BLUE LOGIN 
Route::get('registration', [LoginAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [LoginAuthController::class, 'customRegistration'])->name('register.custom'); 
Route::get('signout', [LoginAuthController::class, 'signOut'])->name('signout');
//ViewLoader controller
Route::get('facturarGeneral',[ViewsController::class, 'facturarGeneral'])->name('facturarGeneral');//RUTA PARA CARGAR MODULO DE FACTURACION
Route::get('claveProdServ',[ViewsController::class, 'claveProdServ'])->name('claveProdServ');//CONFIGURACION DE DATOS DE CATALOGO
Route::get('claveUnidad',[ViewsController::class, 'claveUnidad'])->name('claveUnidad');//CONFIGURACION DE DATOS DE CATALOGO
Route::get('formaPago',[ViewsController::class, 'formaPago'])->name('formaPago');//CONFIGURACION DE DATOS DE CATALOGO
Route::get('metodoPago',[ViewsController::class, 'metodoPago'])->name('metodoPago');//CONFIGURACION DE DATOS DE CATALOGO
Route::get('tipoComprobante',[ViewsController::class, 'tipoComprobante'])->name('tipoComprobante');//CONFIGURACION DE DATOS DE CATALOGO
Route::get('usoCfdi',[ViewsController::class, 'usoCfdi'])->name('usoCfdi');//CONFIGURACION DE DATOS DE CATALOGO
Route::get('tipoRelacion',[ViewsController::class, 'tipoRelacion'])->name('tipoRelacion');//CONFIGURACION DE DATOS DE CATALOGO
Route::get('objetoImpuesto',[ViewsController::class, 'objetoImpuesto'])->name('objetoImpuesto');//CONFIGURACION DE DATOS DE CATALOGO
Route::get('emisor',[ViewsController::class, 'emisor'])->name('emisor');//CONFIGURACION DE DATOS DE EMISOR
Route::get('clientes', [ViewsController::class, 'clientes'])->name('clientes'); // ACTUALIZAR CLIENTES
Route::get('conceptosInternos', [ViewsController::class, 'conceptosInternos'])->name('conceptosInternos'); // conceptosInternos
//actions catalogs
Route::post('guardarCliente',[FacturacionController::class, 'guardarCliente'])->name('guardarCliente');//RUTA PARA GUARDAR CLIENTE
Route::get('updateClaveProdServ/{id}/{value}', [FacturacionController::class, 'updateClaveProdServ'])->name('updateClaveProdServ');//CATALOGO CONFIGURACION UPDATE
Route::get('updateClaveUnidad/{id}/{value}', [FacturacionController::class, 'updateClaveUnidad'])->name('updateClaveUnidad');//CLAVE UNIDAD CONFIGURACION UPDATE
Route::get('updateFormaPago/{id}/{value}', [FacturacionController::class, 'updateFormaPago'])->name('updateFormaPago');//CLAVE UNIDAD CONFIGURACION UPDATE
Route::get('updateMetodoPago/{id}/{value}', [FacturacionController::class, 'updateMetodoPago'])->name('updateMetodoPago');//CLAVE UNIDAD CONFIGURACION UPDATE
Route::get('updateTipoComprobante/{id}/{value}', [FacturacionController::class, 'updateTipoComprobante'])->name('updateTipoComprobante');//CLAVE UNIDAD CONFIGURACION UPDATE
Route::get('updateUsoCfdi/{id}/{value}/{campo}', [FacturacionController::class, 'updateUsoCfdi'])->name('updateUsoCfdi');//CLAVE UNIDAD CONFIGURACION UPDATE
Route::get('updateTipoRelacion/{id}/{value}', [FacturacionController::class, 'updateTipoRelacion'])->name('updateTipoRelacion');//CLAVE UNIDAD CONFIGURACION UPDATE
Route::get('updateObjetoImpuesto/{id}/{value}', [FacturacionController::class, 'updateObjetoImpuesto'])->name('updateObjetoImpuesto');//CLAVE UNIDAD CONFIGURACION UPDATE
Route::post('clientesUpdate', [FacturacionController::class, 'clientesUpdate'])->name('clientesUpdate');//CLAVE UNIDAD CONFIGURACION UPDATE
Route::post('guardarEmisor',[FacturacionController::class, 'guardarEmisor'])->name('guardarEmisor');//GUARDAR DATOS DE EMISOR
Route::post('storeFiles', [FacturacionController::class, 'storeFiles'])->name('storeFiles'); // SUBIR/ACTUALIZAR CERTIFICADOS SAT
Route::get('borrarCliente/{id}', [FacturacionController::class, 'borrarCliente'])->name('borrarCliente'); // BORRAR CLIENTE
Route::post('guardarConceptoInterno',[FacturacionController::class, 'guardarConceptoInterno'])->name('guardarConceptoInterno');// guardarConceptoInterno
Route::get('deleteConceptoInterno/{id}', [FacturacionController::class, 'deleteConceptoInterno'])->name('deleteConceptoInterno');//deleteConceptoInterno

//timbrar controller
Route::post('timbrarFactura', [FacturacionController::class, 'timbrarFactura'])->name('timbrarFactura'); //TIMBRAR FACTURA


//Diarios
Route::get('diarios-contabilizados',[DiariosController::class, 'diarios-contabilizados'])->name('diarios-contabilizados');
