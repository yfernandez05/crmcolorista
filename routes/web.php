<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AsistenciaController;

Route::get('/', 'Auth\LoginController@showLoginForm');
Auth::routes(['register' => false]);
Route::permanentRedirect('/register', '/login');

Route::permanentRedirect('/home', '/crm');

//Route::view('/qr', 'qr/messagereceived')->name('thanks');
//Route::view('/testrute', 'qr/test')->name('thanks');

//Route spa
Route::get('crm/{path?}', "SpaController@index")->where('path', '([A-z0-9-\/_.]+)?')->name('spa');

//Routes for consume data
Route::prefix('rest')->name('rest.')->group(function () {

    Route::get('user/userasesor', 'UserController@userasesor')->name('user.userasesor');
    Route::get('user/select', 'UserController@select')->name('user.select');
    Route::resource('user', 'UserController');

    Route::get('rol/select', 'RolController@select')->name('rol.select');
    Route::resource('rol', 'RolController')->except(['show', 'create']);

    Route::resource('condicion', 'CondicionController');

    Route::resource('alumno', 'AlumnoController');

    Route::resource('turno', 'TurnoController');

    Route::resource('curso', 'CursoController');

    Route::get('carrera/select', 'CarreraController@select')->name('carrera.select');
    Route::resource('carrera', 'CarreraController');

    Route::get('ciclo/select', 'CicloController@select')->name('ciclo.select');
    Route::resource('ciclo', 'CicloController');

    Route::resource('planestudio', 'PlanEstudioController');





    Route::get('tipoAtencion/select', 'TipoAtencionController@select')->name('tipoAtencion.select');
    Route::resource('tipoAtencion', 'TipoAtencionController')->except(['show', 'create']);

    Route::get('atencion/detail/{id}', 'AtencionController@detail')->name('tipoAtencion.detail');
    Route::resource('atencion', 'AtencionController')->except(['show', 'create']);

    Route::get('cuenta/getecampania', 'CuentaController@getecampania')->name('cuenta.getecampania');
    Route::get('cuenta/select', 'CuentaController@select')->name('cuenta.select');
    Route::resource('cuenta', 'CuentaController')->except(['show', 'create']);
    Route::get('campania/select', 'CampaniaController@select')->name('campania.select');
    Route::resource('campania', 'CampaniaController')->except(['show', 'create']);

    Route::get('evento/select', 'EventoController@select')->name('evento.select');
    Route::get('evento/getevento', 'EventoController@getevento')->name('evento.getevento');
    Route::resource('evento', 'EventoController')->except(['edit', 'create']);

    Route::get('cliente/generatecard/{id}','ClienteController@generatecard')->name('cliente.generatecard');
    Route::get('cliente/selectsearch','ClienteController@selectsearch')->name('cliente.selectsearch');
    Route::get('cliente/donwloadqr','ClienteController@donwloadQR')->name('cliente.donwloadqr');
    Route::get('cliente/downloadsingleqr','ClienteController@downloadSingleQR')->name('cliente.downloadSingleQR');
    Route::get('cliente/sedesUni', 'ClienteController@sedesUni')->name('cliente.sedesUni');
    Route::get('cliente/procedenciaAdsUtm', 'ClienteController@procedenciaAdsUtm')->name('cliente.procedenciaAdsUtm');
    Route::get('cliente/descargarplantilla', 'ClienteController@descargarplantilla')->name('cliente.descargarplantilla');
    Route::get('cliente/exportarexcel', 'ClienteController@exportarExcel')->name('cuenta.exportarexcel');
    Route::post('cliente/importarexcel', 'ClienteController@importarExcel')->name('cuenta.importarexcel');
    Route::post('cliente/physicallyremove', 'ClienteController@physicallyRemove')->name('cliente.physicallyremove');
    Route::resource('cliente', 'ClienteController')->except(['show', 'create']);

    Route::resource('clienteevento', 'EventoClienteController');

    Route::get('ubigeo/index', 'UbigeoController@index')->name('ubigeo.index');
    Route::get('ubigeo/departamentos', 'UbigeoController@departamentos')->name('ubigeo.departamentos');
    Route::get('ubigeo/provincias', 'UbigeoController@provincias')->name('ubigeo.provincias');
    Route::get('ubigeo/distritos', 'UbigeoController@distritos')->name('ubigeo.distritos');
    Route::get('ubigeo/distritosleccionados', 'UbigeoController@distritosleccionados')->name('ubigeo.distritosleccionados');

    Route::resource('import', 'ImportacionController')->except(['show', 'create']);
    Route::resource('logerror', 'LogErrorController')->except(['show', 'create']);

    Route::get('reporte/asistenciasedes','ReporteController@asistenciasedes');


    Route::get('reporte/enviosinvitacionwtsp','ReporteController@enviosinvitacionwtsp');
    Route::get('reporte/registrocampainsource','ReporteController@registrocampainsource');
    Route::get('reporte/registroprocedencia','ReporteController@registroprocedencia');
    Route::get('reporte/registrocarrera','ReporteController@registrocarrera');
    Route::get('reporte/registrosede','ReporteController@registrosede');
    Route::get('reporte/registroanioegreso','ReporteController@registroanioegreso');
    Route::get('reporte/cantidadclientesevento','ReporteController@cantidadclientesevento');
    Route::get('reporte/asistenciasevento','ReporteController@asistenciasevento');
    Route::get('reporte/totalcampanias','ReporteController@totalcampanias');
    Route::get('reporte/cantidadprospectos','ReporteController@cantidadprospectos');
    Route::get('reporte/registroclientes','ReporteController@registroclientes');
    Route::get('reporte/clienteCarrera','ReporteController@clienteCarrera');
    Route::get('reporte/clienteedad','ReporteController@clienteedad');
    Route::get('reporte/clienteinstitucion','ReporteController@clienteinstitucion');
    Route::get('reporte/clientegrados','ReporteController@clientegrados');
    Route::get('reporte/cantidadasistenciastand','ReporteController@cantidadasistenciastand');
    Route::get('reporte/registrostandventa','ReporteController@registrostandventa');
    Route::get('reporte/registrostandbeca','ReporteController@registrostandbeca');

    Route::post('validateqr','QrCodeController@decryptQRCode');
    Route::post('markattendanceentry','QrCodeController@markAttendanceEntry');

    Route::get('buttonmessage/select', 'ButtonMessageController@select');
    Route::resource('buttonmessage', 'ButtonMessageController');


    //Route::get('asistencias', [AsistenciaController::class, 'index']);
    //Route::post('asistencias', [AsistenciaController::class, 'store']);
    Route::get('asistencias/select', 'AsistenciaController@select')->name('asistencias.select');
    Route::get('asistencias/alumno/{alumno_id}', [AsistenciaController::class, 'getAsistenciasByAlumno']);
    Route::resource('asistencias', 'AsistenciaController')->except(['show', 'create']);
    Route::get('alumno/generatecard/{id}','AlumnoController@generatecard')->name('alumno.generatecard');

    Route::get('prospecto/select', 'ProspectoController@select')->name('prospecto.select');
    Route::resource('prospecto', 'ProspectoController')->except(['create']);
    Route::resource('seguimiento', 'SeguimientoController')->except(['create']);

    
    Route::resource('conceptopago', 'ConceptoPagoController');
    Route::resource('periodo', 'PeriodoController');

});
