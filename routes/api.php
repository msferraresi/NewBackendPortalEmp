<?php

use Illuminate\Http\Request;
//use Illuminate\Routing\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Login
Route::post('login', 'loginUsuarioController@loginAttempt')->name('login');
Route::post('logout','loginUsuarioController@logout')->name('logout');

//TypeFile
Route::resource('getTypeFilesList','TypeFileController');

//Upload de archivo
//Route::post('pdfUpload', 'uploadFileController@upload')->name('pdfUpload');
Route::post('pdfUpload', 'FileController@upload')->name('pdfUpload');
Route::post('generarPdf', 'FileController@generarPdf')->name('generarPdf');

//Contraseñas
Route::post('setPassword', 'PasswordController@create')->name('setPassword');
Route::post('changePassword','PasswordController@changePassword')->name('changePassword');
Route::post('resetPassword','PasswordController@resetPassword')->name('resetPassword');
