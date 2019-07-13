<?php

namespace arsatapi\Http\Controllers;
use arsatapi\User;
use arsatapi\permisos_x_subcategorias;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermission;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class usuariosController extends Controller
{
    use HasRoles;

    public function index(Request $request, $param = null){
        //Carga vista Usuarios
        $usuarios = $request->busqueda_usr;
        $filtro = $request->filtro;

        $usr = session()->get('usr');
        $usr = $usr[0];

        if($usuarios == '' && $filtro == 'todos'){
        $lst_usuarios = new User();
        $lst_usuarios = User::withTrashed()->orderBy('name', 'asc')->orderBy('lastname', 'asc')->get();
        if($usr){
            return view('usuarios.usuarios', compact('lst_usuarios', 'usr'));
        }
        }
        else if($usuarios != '' && $filtro == 'todos'){
            $lst_usuarios = new User();
            $lst_usuarios = User::withTrashed()->where('name', 'like' , "%$usuarios%")->
            orWhere('lastname', 'like' , "%$usuarios%")->
            orWhere('email', 'like' , "%$usuarios%")->orderBy('name', 'asc')->orderBy('lastname', 'asc')->get();
            if($usr){
                return view('usuarios.usuarios', compact('lst_usuarios', 'usr'));
            }
        }
        else if($usuarios == '' && $filtro == 'con'){
            $lst_usuarios = new User();
            $usuarios = User::all();

            //Filtro usuarios sin permisos
            $lst_usuarios = $usuarios->filter( function($usuario, $key){
                    $permisos = $usuario->getAllPermissions();
                    if( count($permisos) != 0){
                        return true;
                    }
            } );
            if($usr){
                return view('usuarios.usuarios', compact('lst_usuarios', 'usr'));
            }
        }

        else if($usuarios != '' && $filtro == 'con'){
            $lst_usuarios = new User();
            $usuarios = User::where('name', 'like' , "%$usuarios%")->
            orWhere('lastname', 'like' , "%$usuarios%")->
            orWhere('email', 'like' , "%$usuarios%")->orderBy('name', 'asc')->orderBy('lastname', 'asc')->get();

            //Filtro usuarios sin permisos
            $lst_usuarios = $usuarios->filter( function($usuario, $key){
                    $permisos = $usuario->getAllPermissions();
                    if( count($permisos) != 0){
                        return true;
                    }
            } );
            if($usr){
                return view('usuarios.usuarios', compact('lst_usuarios', 'usr'));
            }
        }

        else if($usuarios == '' && $filtro == 'sin'){
            $lst_usuarios = new User();
            $usuarios = User::all();

            //Filtro usuarios sin permisos
            $lst_usuarios = $usuarios->filter( function($usuario, $key){
                    $permisos = $usuario->getAllPermissions();
                    if( count($permisos) == 0){
                        return true;
                    }
            } );
            if($usr){
                return view('usuarios.usuarios', compact('lst_usuarios', 'usr'));
            }
        }

        else if($usuarios != '' && $filtro == 'sin'){
            $lst_usuarios = new User();
            $usuarios = User::where('name', 'like' , "%$usuarios%")->
            orWhere('lastname', 'like' , "%$usuarios%")->
            orWhere('email', 'like' , "%$usuarios%")->orderBy('name', 'asc')->orderBy('lastname', 'asc')->get();

            $lst_usuarios = $usuarios->filter( function($usuario, $key){
                    $permisos = $usuario->getAllPermissions();
                    if( count($permisos) != 0){
                        return true;
                    }
            } );
            if($usr){
                return view('usuarios.usuarios', compact('lst_usuarios', 'usr'));
            }
        }


        else if($param == 'sin_permisos'){
            $lst_usuarios = new User();
            $usuarios = User::orderBy('name', 'asc')->orderBy('lastname', 'asc')->get();
            $lst_usuarios = $usuarios->filter( function($usuario, $key){
                $permisos = $usuario->getAllPermissions();
                if( count($permisos) == 0){
                    return true;
                }
        } );
        if($usr){
            return view('usuarios.usuarios', compact('lst_usuarios', 'usr'));
        }
        }
        else{
            $lst_usuarios = new User();
            $lst_usuarios = User::withTrashed()->orderBy('name', 'asc')->orderBy('lastname', 'asc')->get();
            if($usr){
                return view('usuarios.usuarios', compact('lst_usuarios', 'usr'));
            }
        }
    }

    public function getDetalleUsuario(Request $request){
        $detalle_usr = new User();
        $detalle_usr = $detalle_usr::withTrashed()->where('employeeid', $request->userid)->first();
        return response()->json($detalle_usr);
    }

    public function editUsr($id){
        $usr = session()->get('usr');
        $usr = $usr[0];
        $usr->getRoleNames();

        //Genera Array de permisos agrupados por categorias->subcategorias
        $list_categorias = DB::table('categorias')->select('id' , 'name')->get();
        $array_cat_subcat = array();
        $z=0;
        foreach($list_categorias as $categorias){
            //Subcategorías de cada categoría
            $array_cat_subcat[$z]['id_cat'] = $categorias->id;
            $array_cat_subcat[$z]['nombre_cat'] = $categorias->name;
            $array_cat_subcat[$z]['subcategorias'] = array();

            $subcategorias = DB::table('subcategorias')->where('id_categoria', $categorias->id)
            ->select('id', 'name')->get();

            $array_subs = array(); $j=0;
            foreach($subcategorias as $sub){
                $array_subs[$j]['id_subcat'] = $sub->id;
                $array_subs[$j]['name_subcat'] = $sub->name;
                $array_subs[$j]['permisos'] = DB::table('permissions')->
                join('permisos_x_subcategorias', 'permisos_x_subcategorias.id_per', '=', "permissions.id")->
                join('subcategorias', 'subcategorias.id', '=', 'permisos_x_subcategorias.id_sub')->
                where('subcategorias.id', $sub->id)->
                select('permissions.id', 'permissions.name')->get();
                $j++;
            }
            if(count($subcategorias) > 0){
                array_push($array_cat_subcat[$z]['subcategorias'], $array_subs);
            }
            $array_subs = null;
            $z++;
        }
        //Fin de array

        //Carga de roles y permisos de los mismos
        $glob_roles = Role::all();
        $roles = array();
        foreach($glob_roles as $rol){

            $roles[$j]['idRol'] = $rol['id'];
            $roles[$j]['nomRol'] = $rol['name'];
            $roles[$j]['permisos'] = array();
            $aux = Role::findByName($rol->name)->permissions;
            $permisos = array(); $z = 0;
            foreach($aux as $permiso){
                $permisos[$z]['idPermiso'] = $aux[$z]->id;
                $permisos[$z]['permiso'] = $aux[$z]['name'];
            $z++;
            }
            array_push($roles[$j]['permisos'], $permisos);
            $j++;
        }

        $usuario = new User();
        $usuario = $usuario::withTrashed()->where('employeeid', $id)->first();
        $permisos_usuario = $usuario->getAllPermissions();
        $usuario->permisos = $permisos_usuario;
        $usuario->roles = $usuario->getRoleNames();

        return view('usuarios.usr_permisos', compact('usr', 'usuario', 'permisos_usuario', 'array_cat_subcat', 'roles'));

    }

    public function editPermisoUsr(Request $request){
        $usuario = new User();
        $usuario = session()->get('usuario');

        $estan = []; //Permisos que recibe marcados por checkbox
        $estaban = []; //Permisos que ya tenía el usuario

        if( $usuario->employeeid == auth()->user()->employeeid ){
            $notificacion = array(
                'message' => 'No puede modificar sus permisos.',
                'alert-type' => 'error'
            );
            return back()->with('notificacion' ,$notificacion);
        }
        $permisos_actuales = $usuario->getAllPermissions();
        if( ($request->checkbox) != null ){
        foreach($request->checkbox as $peticion){
            if( count($permisos_actuales) > 0){
            foreach($permisos_actuales as $permiso){
                if($permiso->id == $peticion){
                    //Obtiene todos los elementos tildados como un array de nombres de permisos.
                    array_push($estan, $permiso->name);
                }
            }
            }
            //Verifica los permisos tildados y los asigna al rol
            $nombre_nuevo_permiso = Permission::where('id', $peticion)->select('name')->first();
            $nombre_nuevo_permiso = $nombre_nuevo_permiso->name;
            print_r($nombre_nuevo_permiso);
            $usuario->givePermissionTo($nombre_nuevo_permiso);
        }
        }
        if( count($permisos_actuales) > 0 ){
            foreach($permisos_actuales as $permi){
                array_push($estaban, $permi->name);
            }
        }
        $diferencia = array_diff($estaban, $estan);
        if( $diferencia > 0 ){
            foreach($diferencia as $nombre){
                $usuario->revokePermissionTo($nombre);
            }
        }

        //notificacion toaster
        $notificacion = array(
            'message' => 'Permisos modificados correctamente.',
            'alert-type' => 'success'
        );
        return back()->with('notificacion' , $notificacion);
    }

    public function bloquearUsr($id){
        $usuario = User::where('employeeid' , $id)->first();
        $usuario->delete();

        $notificacion = array(
            'message' => 'El usuario ' . $usuario->name . ' ' . $usuario->lastname .  ' ha sido dado de baja satisfactoriamente.',
            'alert-type' => 'success'
        );
        return back()->with('notificacion' , $notificacion);
    }

    public function habilitarUsr($id){
        $restaurar = User::onlyTrashed()->where('employeeid', $id)->first();
        if($restaurar){
            $restaurar->restore();
            $notificacion = array(
                'message' => 'El usuario ' . $restaurar->name . ' ' . $restaurar->lastname . ' fue restaurado correctamente.',
                'alert-type' => 'success'
            );
            return back()->with('notificacion', $notificacion);
        }
        $notificacion = array(
            'message' => 'Error al habilitar el usuario.',
            'alert-type' => 'error'
        );
        return back()->with('notificacion', $notificacion);
    }

    //public function
}
