<?php

namespace arsatapi\Http\Controllers;
use Auth;
use Illuminate\Support\Facades\DB;
use arsatapi\Categoria;
use arsatapi\subcategoria;
use arsatapi\subcategorias_x_categorias;
use Illuminate\Http\Request;

class categoriasController extends Controller
{
    public function categorias(){
        $usr = session()->get('usr');
        $usr = $usr[0];
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
                $j++;
            }

            if(count($subcategorias) > 0){
                array_push($array_cat_subcat[$z]['subcategorias'], $array_subs);
            }
            $array_subs = null;
            $z++;

        }

        activity()->log('function categorias() user: ' . $usr->email);

        return view('Categorias.categorias', compact('usr','array_cat_subcat'));
    }

    public function setcategorias(Request $request){
        $usr = session()->get('usr');

        $valid_req = $this->validate(request(),[
            'nombre_categoria' => 'max:60 | regex:/[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+/',
        ]);


        $categoria = new Categoria();
        $categoria->name = $request->nombre_categoria;
        $control = Categoria::where('name', $categoria->name)->get();

        if( count($control) == 0 ){
            $flag = $categoria->save();

            if($flag){
                $notificacion = array(
                    'message' => 'La categorÍa fue creada exitosamente.',
                    'alert-type' => 'success'
                );
                activity()->log('function setcategorias(Request $request) mensaje: La categorÍa fue creada exitosamente. user: ' . $usr->email);
                return back()->with('notificacion', $notificacion);
            }

            $notificacion = array(
                'message' => 'Ocurrió un error durante la creación de la categorÍa.',
                'alert-type' => 'error'
            );
            activity()->log('function setcategorias(Request $request) mensaje: Ocurrió un error durante la creación de la categorÍa. user: ' . $usr->email);
            return back()->with('notificacion', $notificacion)->withInput();
        }
        $notificacion = array(
            'message' => 'La categorÍa que desea crear ya existe.',
            'alert-type' => 'error'
        );
        activity()->log('function setcategorias(Request $request) mensaje: La categorÍa que desea crear ya existe. user: ' . $usr->email);
        return back()->with('notificacion', $notificacion)->withInput();
    }


    public function subcategorias(){
        $usr = session()->get('usr');
        $usr = $usr[0];

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
                $permisos = DB::table('permissions')->
                join('permisos_x_subcategorias', 'id_per', '=', 'permissions.id')
                ->where('permisos_x_subcategorias.id_sub', $sub->id)
                ->get();
                $permisos = $permisos->toArray();
                $array_subs[$j]['id_subcat'] = $sub->id;
                $array_subs[$j]['name_subcat'] = $sub->name;
                $array_subs[$j]['permisos'] = $permisos;
                $j++;
            }

            if(count($subcategorias) > 0){
                array_push($array_cat_subcat[$z]['subcategorias'], $array_subs);
            }
            $array_subs = null;
            $z++;

        }
        //dd($array_cat_subcat);
        $categorias = Categoria::all();
        $list_subcategorias = subcategoria::all();

        activity()->log('function subcategorias() user: ' . $usr->email);
        return view('Categorias.subcategorias', compact('usr', 'categorias', 'list_subcategorias', 'array_cat_subcat'));
    }

    public function setsubcategorias(Request $request){
        $usr = session()->get('usr');
        $valid_req = $this->validate(request(),[
            'nombre_subcategoria' => 'max:60 | regex:/[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+/',
        ]);


        $subcategoria = new subcategoria();
        $subcategoria->name = $request->nombre_subcategoria;
        $subcategoria->id_categoria = $request->categoria;

        $control = subcategoria::where('name', $request->nombre_subcategoria)->get();

        if( count($control)==0 ){
            $flag = $subcategoria->save();

            if($flag){
                $notificacion = array(
                    'message' => 'La sub-categoría fue creada exitosamente.',
                    'alert-type' => 'success'
                );
                activity()->log('function setsubcategorias(Request $request) mensaje: La sub-categoría fue creada exitosamente. user: ' . $usr->email);
                return back()->with('notificacion', $notificacion);
            }

            $notificacion = array(
                'message' => 'Ocurrió un error durante la creación de la sub-categoría.',
                'alert-type' => 'error'
            );
            activity()->log('function setsubcategorias(Request $request) mensaje: Ocurrió un error durante la creación de la sub-categoría. user: ' . $usr->email);
            return back()->with('notificacion', $notificacion)->withInput();
        }
        $notificacion = array(
            'message' => 'La sub-categorÍa que desea crear ya existe.',
            'alert-type' => 'error'
        );
        activity()->log('function setsubcategorias(Request $request) mensaje: La sub-categorÍa que desea crear ya existe. user: ' . $usr->email);
        return back()->with('notificacion', $notificacion)->withInput();

    }

    public function getsubcategorias(Request $request){
        $usr = session()->get('usr');
        $sub_cat = new subcategoria();
        $subcategorias = DB::table('subcategorias')->
        join('categorias', 'categorias.id', '=', 'subcategorias.id_categoria')->where('categorias.id', $request->categoria)->
        select('subcategorias.id','subcategorias.name' )->get();
        activity()->log('function getsubcategorias(Request $request) user: ' . $usr->email);
        return response()->json($subcategorias);
    }

    public function editCategoria($id){
        $usr = session()->get('usr');
        $usr = $usr[0];
        $categoria = Categoria::where('id' , $id)->first();
        activity()->log('function editCategoria($id) user: ' . $usr->email);
        return view('Categorias.editCategoria')->with('categoria', $categoria)->with('usr', $usr);

    }

    public function editarCategoria(Request $request){
        $usr = session()->get('usr');
        $validar = $this->validate(request(),[
            'nombreCategoria' => 'required | max:60 | regex:/[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+/'
        ]);

        $flag = Categoria::where('id', $request->categoria)->update(['name' => $request->nombreCategoria]);
        if($flag == 1){
            $notificacion = array(
                'message' => 'Categoría modificada correctamente.',
                'alert-type' => 'success'
            );
            activity()->log('function editarCategoria(Request $request) mensaje: Categoría modificada correctamente. user: ' . $usr->email);
            return back()->with('notificacion' ,$notificacion);

        }else{
            $notificacion = array(
                'message' => 'Error al modificar la categoría.',
                'alert-type' => 'warning'
            );
            activity()->log('function editarCategoria(Request $request) mensaje: Error al modificar la categoría. user: ' . $usr->email);
            return back()->with('notificacion' ,$notificacion);
        }
    }

    public function eliminarCategoria(Request $request){
        $usr = session()->get('usr');
        $id = $request->catid;
        $cat = new Categoria();
        $cat = Categoria::where('id', $id)->first();
        $flag = $cat->forceDelete();
        activity()->log('function eliminarCategoria(Request $request) user: ' . $usr->email);
        return response()->json(array(
            'categoria' => $cat,
            'flag' => $flag
        ));
    }

    public function editSubcategoria($id){
        $usr = session()->get('usr');
        $usr = $usr[0];
        $subcat = new subcategoria();
        $subcat = subcategoria::where('id', $id)->first();
        activity()->log('function editSubcategoria($id) user: ' . $usr->email);
        return view('Categorias.editSubcategoria')
        ->with('subcat', $subcat)
        ->with('usr', $usr);
    }

    public function editarSubcategoria(Request $request){
        $usr = session()->get('usr');
        $validar = $this->validate(request(),[
            'nombreSubcat' => 'required | max:60 | regex:/[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+/'
        ]);

        $flag = subcategoria::where('id', $request->subcat)->update(['name' => $request->nombreSubcat]);

        if($flag == 1){

            $notificacion = array(
                'message' => 'Sub-categoría modificada correctamente.',
                'alert-type' => 'success'
            );
            activity()->log('function editarSubcategoria(Request $request) mensaje: Sub-categoría modificada correctamente. user: ' . $usr->email);
            return back()->with('notificacion' ,$notificacion);

        }else{
            $notificacion = array(
                'message' => 'Error al modificar la Sub-categoría.',
                'alert-type' => 'success'
            );
            activity()->log('function editarSubcategoria(Request $request) mensaje:Error al modificar la Sub-categoría. user: ' . $usr->email);
            return back()->with('notificacion' ,$notificacion);
        }

    }

    public function eliminarSubcategoria(Request $request){
        $usr = session()->get('usr');
        $id = $request->subcatid;
        //return response()->json($id);
        $subcat = new subcategoria();
        $subcat = subcategoria::where('id', $id)->first();
        //return response()->json([$id, $subcat]);
        $flag = $subcat->forceDelete();
        activity()->log('function eliminarSubcategoria(Request $request) user: ' . $usr->email);
        return response()->json(array(
            'subcategoria' => $subcat,
            'flag' => $flag
        ));
    }

}
