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

Route::get('/', 'MapaController@index');
Route::get('/Mapa', 'MapaController@index');

Route::get('/Mapa/conseguir', 'MapaController@conseguir');
Route::get('/Mapa/busquedaSVG', 'MapaController@busquedaSVG');
Route::get('/Mapa/resumen', 'MapaController@resumen');
Route::post('/Mapa/poblacion', 'MapaController@poblacion');

Route::get('/Administracion', function () {
  return view('pags.administracion');
})->middleware('auth', 'rol:1,3');
Route::get('/Ajustes', function () {
  return view('pags.ajustes');
})->middleware('auth', 'rol:1,2');

Route::get('/Administracion/InfoElectoral', 'FilasElectoralesController@index');
Route::post('/Administracion/InfoElectoral', 'FilasElectoralesController@store');
Route::put('/Administracion/InfoElectoral', 'FilasElectoralesController@update');
Route::delete('/Administracion/InfoElectoral', 'FilasElectoralesController@destroy');

Route::get('/Administracion/Lideres', 'LideresController@index');
Route::post('/Administracion/Lideres', 'LideresController@store');
Route::put('/Administracion/Lideres', 'LideresController@update');
Route::delete('/Administracion/Lideres', 'LideresController@destroy');

Route::get('/Administracion/Compromisos', 'CompromisosController@index');
Route::post('/Administracion/Compromisos', 'CompromisosController@store');
Route::put('/Administracion/Compromisos', 'CompromisosController@update');
Route::delete('/Administracion/Compromisos', 'CompromisosController@destroy');

Route::get('/Administracion/Corporaciones', 'CorporacionesController@index');
Route::post('/Administracion/Corporaciones', 'CorporacionesController@store');
Route::put('/Administracion/Corporaciones', 'CorporacionesController@update');
Route::delete('/Administracion/Corporaciones', 'CorporacionesController@destroy');

Route::get('/Administracion/Visitas', 'VisitasController@index');
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


Route::get('/ExportarFilasElectorales', 'ExportExcelController@filasElectorales');
Route::get('/ExportarLideres', 'ExportExcelController@lideres');
Route::get('/ExportarCompromisos', 'ExportExcelController@compromisos');
Route::get('/ExportarCorporaciones', 'ExportExcelController@corporaciones');
Route::get('/ExportarUsuarios', 'ExportExcelController@usuarios');
Route::get('/ExportarRoles', 'ExportExcelController@roles');
Route::post('/Importar/{cosa}', 'ImportExcelController@importar');


Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');