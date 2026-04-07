<?php
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Auth::routes();
Route::get('/', 'frontend\TramitesController@index');
Route::get('inicio', 'frontend\TramitesController@index')->name('inicio');
Route::get('/home', 'HomeController@index');
Route::get('/homepage', 'HomeController@index');
//Route::get('/perfil', 'PerfilController@index');
Route::get('turnos', 'frontend\TramitesController@index')->name('turnos');
//Route::get('turnos.dni', 'frontend\TramitesController@solicitarDni');
Route::post('turnos.guardar','frontend\TramitesController@guardar');
Route::post('turnos.loadhorarios', 'frontend\TramitesController@loadHorarios')->name('turnos.loadhorarios');
Route::post('turnos.getdisableddates', 'frontend\TramitesController@getDisabledDates')->name('turnos.getdisableddates');

Route::get('turnos.print/{id}', 'frontend\TramitesController@print')->name('turnos.print');//Valido

Route::get('tramite.index','frontend\TramitesController@index')->name('tramite.index');//Valido
Route::get('tramite.paso2','frontend\TramitesController@paso2')->name('tramite.paso2');//Valido
Route::get('tramite.paso3','frontend\TramitesController@paso3')->name('tramite.paso3');//Valido
Route::post('tramite.guardar','frontend\TramitesController@guardar')->name('tramite.guardar');//Valido
Route::get('tramite/confirmacion/{id}', 'frontend\TramitesController@confirmacion')->name('tramite.confirmacion');

Route::get('turno.buscar','frontend\TramitesController@buscar')->name('turno.buscar');//Valid

Route::get('getdirecciones','frontend\TramitesController@getDirecciones')->name('getdirecciones');//valid

Route::get('gettramites/{id}','frontend\TramitesController@getTramites')->name('gettramites');//valid

  
Route::group(['middleware' => 'auth'], function(){

  Route::resource('feriados', 'FeriadosController');//Valido


  Route::resource('turnostramites', 'TurnosTramitesController');//Valido
  Route::resource('turnoshorarios', 'TurnosHorariosController')->shallow();//Valido
  Route::get('listar/{id}','TurnosHorariosController@listar')->name('turnos.horarios.listar');//valid
  Route::resource('tramitesdependencias', 'TramitesDependenciasController');//Valido

  Route::resource('turnosdependenciasreservas', 'TurnosDependenciasReservasController');//Valido
  Route::post('turnosdependenciasreservas.buscar', 'TurnosDependenciasReservasController@buscar')->name('turnosdependenciasreservas.buscar');//Valido
 Route::post('turnosdependenciasreservas.print', 'TurnosDependenciasReservasController@print')->name('turnosdependenciasreservas.print');//Valido

  Route::resource('tramites', 'TramitesDigitalesController');//Valido
  Route::post('tramites.buscar', 'TramitesDigitalesController@buscar')->name('tramites.buscar');//Valido
  Route::get('tramites.descargar/{id}', 'TramitesDigitalesController@descargar')->name('tramites.descargar');//Valido

  Route::resource('pases', 'PasesController');//Valido
  Route::get('pases.listar/{tramite_id}', 'PasesController@listar')->name('pases.listar');//Valido

  Route::get('reporte.operador','ReporteOperadorController@index');
  Route::post('listado.operadores','ReporteOperadorController@listado_operadores');
  Route::post('listado.imprimir_listado','ReporteOperadorController@imprimir_listado');

	Route::resource('permisos', 'PermisosController');
  Route::resource('roles', 'RolesController');
  Route::resource('rolespermisos','RolesPermisosController');
  Route::resource('dependencias','DependenciasController');
  Route::resource('usuarios', 'UsuariosController');
  Route::get('usuarios.buscar', 'UsuariosController@buscar')->name('usuarios.buscar');
  
  
  Route::get('usuariosdependencias.index/{id_usuario}','UsuariosDependenciasController@index');//Valido
  Route::post('usuariosdependencias.guardar','UsuariosDependenciasController@guardar');//Valido
   Route::resource('mesashabilitadas', 'MesasHabilitadasController');//Valido

  Route::get('usuariosroles.index/{id_usuario}','UsuariosRolesController@index');
  Route::post('usuariosroles.guardar','UsuariosRolesController@guardar');

  Route::get('usuarios.mi_perfil','UsuariosController@mi_perfil');
  Route::post('usuarios.update_perfil','UsuariosController@update_perfil');
  Route::get('usuarios.edit_password/{usuario_id}','UsuariosController@cambiarPassword');
  Route::post('usuarios.store_password','UsuariosController@storePassword');

  // Turnos Admin Routes
  Route::resource('turnos_admin', 'TurnosController');
  Route::get('turnos_admin.llamar_siguiente', 'TurnosController@llamar_siguiente')->name('turnos_admin.llamar_siguiente');
  Route::get('turnos_admin.terminar_turno/{id_turno}', 'TurnosController@terminar_turno')->name('turnos_admin.terminar_turno');
  Route::get('turnos_admin.es_afiliado/{id_turno}', 'TurnosController@es_afiliado')->name('turnos_admin.es_afiliado');
});

Route::get('auth/google', 'Auth\LoginController@redirectToGoogle')->name('google.login');
Route::get('auth/google/callback', 'Auth\LoginController@handleGoogleCallback')->name('google.callback');

