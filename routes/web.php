<?php
use Symfony\Component\HttpKernel\Fragment\RoutableFragmentRenderer;

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

Route::middleware(\TSA\Http\Middleware\SessionHasUser::class)->group(function () {
    Route::get('/home', 'inicioController@index')->name('home');
    Route::post('/getUsuarioMenu', 'sidebarController@usuariosMenu');
});

 Auth::routes();
 Route::get('/', function () {
    return view('login');
});

Route::post('loginAttempt', 'loginUsuarioController@loginAttempt')->name('loginAttempt');
Route::post('logout', 'loginUsuarioController@logout')->name('logout');


//Resto de los accesos para el administrador
Route::group(['middleware' => ['role:super-admin']], function () {
Route::get('/usuarios/{param?}', 'usuariosController@index')->name('usuarios');
Route::post('/usuarios', 'usuariosController@index')->name('buscarusuarios');
Route::get('/permisos', 'permisosController@permisos')->name('permisos');
Route::post('/setPermiso', 'permisosController@setPermiso')->name('setPermiso');
Route::get('/roles', 'permisosController@roles')->name('roles');
Route::get('/categorias', 'categoriasController@categorias')->name('categorias');
Route::get('/subcategorias', 'categoriasController@subcategorias')->name('subcategorias');
Route::post('/setcategorias', 'categoriasController@setcategorias' )->name('nuevacategoria');
Route::post('/setsubcategorias', 'categoriasController@setsubcategorias' )->name('nuevasubcategoria');
Route::post('/editarRol', 'permisosController@editarRol')->name('editarRol');
Route::post('/modificarRol', 'permisosController@modificarRol')->name('modificarRol');
Route::get('/editarUsuario/{id}', 'usuariosController@editUsr')->name('editUsr');
Route::post('/modificarPermisoUsuario' , 'usuariosController@editPermisoUsr')->name('modificarPermisosUsuario');
Route::get('/bloquearUsr/{id}', 'usuariosController@bloquearUsr' )->name('bloquearUsr');
Route::get('/habilitarUsr/{id}', 'usuariosController@habilitarUsr' )->name('habilitarUsr');
Route::get('editCategoria/{id}', 'categoriasController@editCategoria')->name('editCategoria');
Route::get('editSubcategoria/{id}', 'categoriasController@editSubcategoria')->name('editSubcategoria');
Route::post('editarCategoria', 'categoriasController@editarCategoria')->name('editarCategoria');
Route::post('editarSubcategoria', 'categoriasController@editarSubcategoria')->name('editarSubcategoria');

//Peticiones Ajax
Route::post('/getsubcategorias', 'categoriasController@getsubcategorias' );
Route::post('/getdetalleusuario', 'usuariosController@getDetalleUsuario');
Route::post('crearRol', 'permisosController@crearRol')->name('crearRol');
Route::post('/asignarRol', 'permisosController@asignarRol')->name('asignarRol');
Route::post('/quitarRol', 'permisosController@quitarRol')->name('quitarRol');
Route::post('/eliminarCategoria', 'categoriasController@eliminarCategoria')->name('eliminarCategoria');
Route::post('/eliminarSubcategoria', 'categoriasController@eliminarSubcategoria')->name('eliminarSubcategoria');
});
