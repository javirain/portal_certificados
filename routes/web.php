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

/*
 * HOMES, SESIONES Y OTRAS COSAS MÁGICAS
 */
Route::post('login', 'HomeController@login');
Route::get('logout', 'HomeController@logout');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@index');
Route::post('contacto-mesa-ayuda', 'HomeController@correo_mesa_ayuda')->name('mesa-ayuda');
Route::post('cambiar-clave', 'HomeController@cambiar_clave');
/*
 * CERTIFICADOS
 */
Route::get('certificados', 'CertificadoController@inicio')->name('certificados');
Route::get('certificados-gratis', 'CertificadoController@certificados_gratis');
Route::get('certificados-gratis-emitidos', 'CertificadoController@certificados_gratis_emitidos');
Route::get('certificados-pago', 'CertificadoController@certificados_pago');
Route::get('certificados-pago-emitidos', 'CertificadoController@certificados_pago_emitidos');
Route::get('certificados-actuales', 'CertificadoController@certificados_actuales');
Route::get('certificados-vigentes', 'CertificadoController@certificados_vigentes');


Route::post('send','mailController@send');
Route::get('email','mailController@email');
