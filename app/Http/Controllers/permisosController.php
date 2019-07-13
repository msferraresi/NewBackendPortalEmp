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

class permisosController extends Controller
{
    public function permisos(){
        //Carga vista permisos -> Gestion de permisos
        $usr = session()->get('usr');
        $usr = $usr[0];
        //Carga los permisos existentes
        //Carga las categorias existentes
        $list_categorias = DB::table('categorias')->select('id' , 'name')->get();

        $list_categorias = DB::table('categorias')->select('id' , 'name')->get();
        $array_cat_subcat = array();
        $z=0;
        foreach($list_categorias as $categorias){
            //Subcategorías de cada categoría
            $array_cat_subcat[$z]['id_cat'] = $categorias->id;
            $array_cat_subcat[$z]['nombre_cat'] = $categorias->name;
            $array_cat_subcat[$z]['subcategorias'] = array();

            $subcategorias = DB::table('subcategorias')->
            join('categorias', 'categorias.id', '=', 'subcategorias.id_categoria')->where('categorias.id', $categorias->id)->
            select('subcategorias.id','subcategorias.name' )->get();

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

        activity()->log('function permisos() user: ' . $usr->email);
        return view('roles.permisos', compact('usr'),compact('array_cat_subcat', 'list_categorias'));
    }

    public function setPermiso(Request $request){
        //captura nombre_permiso y lo guarda
        $usr = session()->get('usr');
        $usr = $usr[0];

        $valid_req = $this->validate(request(),[
            'nombre_permiso' => 'max:60 | regex:/[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+/ | required',
            'categoria' => 'required',
            'subcategoria' => 'required'
        ]);
        $permiso = new Permission();
        $permiso = Permission::where('name', $request->nombre_permiso)->first();
        $permiso_x_subcategoria = new permisos_x_subcategorias();
        if($permiso){
            $notificacion = array(
                'message' => 'El permiso que desea crear ya existe.',
                'alert-type' => 'error'
            );
            activity()->log('function setPermiso(Request $request) mensaje: El permiso que desea crear ya existe. user: ' . $usr->email);
            return redirect('/permisos')->withInput()->with('notificacion', $notificacion);
        }
        else{
            $permiso = Permission::create(['name' => $request->nombre_permiso]);
            $permiso = Permission::where('name', $request->nombre_permiso)->first();

            $permiso_x_subcategoria = new permisos_x_subcategorias();
            $permiso_x_subcategoria->id_per = $permiso->id;
            $permiso_x_subcategoria->id_sub = $request->subcategoria;
            $permiso_x_subcategoria->save();
            $notificacion = array(
                'message' => 'El permiso fue creado correctamente.',
                'alert-type' => 'success'
            );
            activity()->log('function setPermiso(Request $request) mensaje: El permiso fue creado correctamente. user: ' . $usr->email);
            return back()->with('notificacion',$notificacion);
        }
    }

    public function roles(){
        $j = 0;
        //Carga vista roles -> Gestion de roles y permisos
        $usr = session()->get('usr');
        $usr = $usr[0];
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
        activity()->log('function setPermiso(Request $request) mensaje: El permiso que desea crear ya existe. user: ' . $usr->email);
        return view('roles.roles', compact('usr'),compact('roles'));
    }

    public function editarRol(Request $request){
        $rol = new Role();
        $usr = session()->get('usr');
        $usr = $usr[0];
        $rol = Role::where('id', $request->id_rol)->select('id', 'name')->first();
        $permisos_rol = $rol->permissions;

        $list_categorias = DB::table('categorias')->select('id' , 'name')->get();
        $array_cat_subcat = array();
        $z=0;
        foreach($list_categorias as $categorias){
            //Subcategorías de cada categoría
            $array_cat_subcat[$z]['id_cat'] = $categorias->id;
            $array_cat_subcat[$z]['nombre_cat'] = $categorias->name;
            $array_cat_subcat[$z]['subcategorias'] = array();

            $subcategorias = DB::table('subcategorias')->
        join('categorias', 'categorias.id', '=', 'subcategorias.id_categoria')->where('categorias.id', $categorias->id)->
        select('subcategorias.id','subcategorias.name' )->get();

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
    return view('roles.editar_rol', compact('rol', 'permisos_rol', 'usr', 'array_cat_subcat'));
    }

    public function modificarRol(Request $request){
        $permisos_rol = new Role();
        $permisos_rol = Role::where('id', $request->id_rol)->select('id', 'name')->first();
        $rol = new Role();
        $rol = $permisos_rol;
        $permisos_rol = $permisos_rol->permissions;

        $peticiones = $request->input();
        $estan = [];
        $no_estan = [];
        foreach($peticiones['checkbox'] as $peticion){
            if( count($permisos_rol) > 0){
            foreach($permisos_rol as $permiso){
                if($permiso->id == $peticion){
                    //Obtiene todos los elementos tildados como un array de nombres de permisos.
                    array_push($estan, $permiso->name);
                }
            }
            }
            //Verifica los permisos tildados y los asigna al rol
            $nombre_nuevo_permiso = Permission::where('id', $peticion)->select('name')->first();
            $nombre_nuevo_permiso = $nombre_nuevo_permiso->name;
            $rol->givePermissionTo($nombre_nuevo_permiso);
        }
        //Obtiene los permisos que tenía el rol.
        $permisos_rol = $permisos_rol->toArray();
        $nombre_permisos = [];
        foreach($permisos_rol as $aux){
            array_push($nombre_permisos, $aux['name']);
        }
        //Compara los permisos que tenía el rol con los nuevos, si existe una diferencia,
        //revoca el permiso en cuestion.
        $diferencia = array_diff($nombre_permisos, $estan);
        foreach($diferencia as $dif){
            $rol->revokePermissionTo($dif);
        }
        $notificacion = array(
            'message' => 'El rol ha sido modificado correctamente',
            'alert-type' => 'success'
        );
        return redirect('/roles')->with( 'notificacion' , $notificacion);
    }

    public function crearRol(Request $request){

        $valid_req = $this->validate(request(),[
            'rol' => 'max:60 | regex:/[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+/',
        ]);

        $flag = Role::where('name', $request->rol)->first();
        if($flag){
            $notificacion = array(
                'message' => 'El nombre que desea utilizar ya existe.',
                'alert-type' => 'error'
            );
            return back()->with('notificacion' , $notificacion);
        }
        Role::create(['name' => $request->rol]);

        $notificacion = array(
            'message' => 'El rol ha sido creado correctamente.',
            'alert-type' => 'success'
        );
        return back()->with('notificacion', $notificacion);
    }

    public function asignarRol(Request $request){
        $rol = new Role();
        $rol = $rol->where('id', $request->rolid)->first();
        $usuario = new User();
        $usuario = session()->get('usuario');
        $permisos_rol_asignado = Role::findByName($rol->name)->permissions;

        foreach($permisos_rol_asignado as $nuevoPermiso){
        $usuario->givePermissionTo($nuevoPermiso->name);
        }

        $usuario->assignRole($rol->name);
        return response()->json( 'ok' );
    }

    public function quitarRol(Request $request){
        $rol = new Role();
        $rol = $rol->where('id', $request->rolid)->first();
        $usuario = new User();
        $usuario = session()->get('usuario');

        $permisos_rol_asignado = Role::findByName($rol->name)->permissions;

        foreach($permisos_rol_asignado as $nuevoPermiso){
        $usuario->revokePermissionTo($nuevoPermiso->name);
        }

        $usuario->removeRole($rol->name);
        return response()->json( 'quitar' );
    }

}
