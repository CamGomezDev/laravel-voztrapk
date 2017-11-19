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

Route::get('/', 'MapaAntController@index');

Route::get('/Mapa/Ant', 'MapaAntController@index');
Route::get('/Mapa/Ant/conseguir', 'MapaAntController@conseguir');
Route::get('/Mapa/Ant/busquedaSVG', 'MapaAntController@busquedaSVG');
Route::get('/Mapa/Ant/resumen', 'MapaAntController@resumen');
Route::post('/Mapa/Ant/poblacion', 'MapaAntController@poblacion');

Route::get('/Mapa/Subregion/{id}', 'MapaAntController@subregion');


Route::get('/Mapa/Med', 'MapaMedController@index');
Route::get('/Mapa/Med/conseguir', 'MapaMedController@conseguir');
Route::get('/Mapa/Med/busquedaSVG', 'MapaMedController@busquedaSVG');
Route::get('/Mapa/Med/resumen', 'MapaMedController@resumen');
Route::post('/Mapa/Med/comuna', 'MapaMedController@comuna');

Route::get('/Subregiones/{id}', 'SubregionesController@index');

Route::get('/Administracion/{sec}', function ($sec) {
  switch ($sec) {
    case 'Med':
      return view('pags.administracion')->with('sec', $sec)
                                        ->with('secNom', 'Medellín');
      break;
    
    default:
      return view('pags.administracion')->with('sec', 'Ant')
                                        ->with('secNom', 'Antioquia');
      break;
  }
})->middleware('auth', 'rol:1,3');
Route::get('/Ajustes', function () {
  return view('pags.ajustes');
})->middleware('auth', 'rol:1,2');

Route::get('/Administracion/{sec}/InfoElectoral', 'FilasElectoralesController@index');
Route::post('/Administracion/{sec}/InfoElectoral', 'FilasElectoralesController@store');
Route::put('/Administracion/{sec}/InfoElectoral', 'FilasElectoralesController@update');
Route::delete('/Administracion/InfoElectoral', 'FilasElectoralesController@destroy');

Route::get('/Administracion/{sec}/Lideres', 'LideresController@index');
Route::post('/Administracion/{sec}/Lideres', 'LideresController@store');
Route::put('/Administracion/{sec}/Lideres', 'LideresController@update');
Route::delete('/Administracion/Lideres', 'LideresController@destroy');

Route::get('/Administracion/{sec}/Compromisos', 'CompromisosController@index');
Route::post('/Administracion/Compromisos', 'CompromisosController@store');
Route::put('/Administracion/Compromisos', 'CompromisosController@update');
Route::delete('/Administracion/Compromisos', 'CompromisosController@destroy');

Route::get('/Administracion/{sec}/Corporaciones', 'CorporacionesController@index');
Route::post('/Administracion/Corporaciones', 'CorporacionesController@store');
Route::put('/Administracion/Corporaciones', 'CorporacionesController@update');
Route::delete('/Administracion/Corporaciones', 'CorporacionesController@destroy');

Route::get('/Administracion/{sec}/Visitas', 'VisitasController@index');
Route::post('/Administracion/Visitas', 'VisitasController@store');
Route::put('/Administracion/Visitas', 'VisitasController@update');
Route::delete('/Administracion/Visitas', 'VisitasController@destroy');

Route::get('/Ajustes/Usuarios', 'UsersController@index');
Route::post('/Ajustes/Usuarios', 'UsersController@store');
Route::put('/Ajustes/Usuarios', 'UsersController@update');
Route::delete('/Ajustes/Usuarios', 'UsersController@destroy');

Route::get('/Ajustes/Roles', 'RolesController@index');
Route::post('/Ajustes/Roles', 'RolesController@store');
Route::put('/Ajustes/Roles', 'RolesController@update');
Route::delete('/Ajustes/Roles', 'RolesController@destroy');


Route::get('/ExportarFilasElectoralesAnt', 'ExportExcelController@filasElectoralesAnt');
Route::get('/ExportarFilasElectoralesMed', 'ExportExcelController@filasElectoralesMed');
Route::get('/ExportarLideresAnt', 'ExportExcelController@lideresAnt');
Route::get('/ExportarLideresMed', 'ExportExcelController@lideresMed');
Route::get('/ExportarCompromisosAnt', 'ExportExcelController@compromisosAnt');
Route::get('/ExportarCompromisosMed', 'ExportExcelController@compromisosMed');
Route::get('/ExportarCorporaciones', 'ExportExcelController@corporaciones');
Route::get('/ExportarVisitas', 'ExportExcelController@visitas');
Route::get('/ExportarUsuarios', 'ExportExcelController@usuarios');
Route::get('/ExportarRoles', 'ExportExcelController@roles');
Route::get('/ExportarLideresMapaAnt/{id}', 'ExportExcelController@lideresMapaAnt');
Route::get('/ExportarLideresMapaMed/{id}', 'ExportExcelController@lideresMapaMed');
//Truco sucio que funciona por suerte ^^ (la ruta /ExportarLideresMapaSubregion no da):
Route::get('/Mapa/ExportarLideresMapaAnt/{id}', 'ExportExcelController@lideresMapaSub');
Route::get('/ExportarFilasElectoralesMapaAnt/{id}', 'ExportExcelController@filasElectoralesMapaAnt');
Route::get('/ExportarFilasElectoralesMapaMed/{id}', 'ExportExcelController@filasElectoralesMapaMed');
Route::get('/Mapa/ExportarFilasElectoralesMapaAnt/{id}', 'ExportExcelController@filasElectoralesMapaSub');
Route::get('/ExportarResumenSub', 'ExportExcelController@resumenSub');
Route::get('/ExportarResumenCom', 'ExportExcelController@resumenCom');


Route::post('/Importar/{cosa}', 'ImportExcelController@importar');


Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');