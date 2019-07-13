@extends('themes.lte.layout')
@section('title', '- Sub-Categorias')
@section('content')
<div class="container-fluid">
<div class="row text-center">
        <h1>Gestión de sub-categorías</h1>
    </div>
    <form action="{{url('/setsubcategorias')}}" method="POST" class="form-group">
        @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            @csrf
            <div class="row">
                <div class="col-md-2">
                        <label for="nombre_subcategoria" style="margin-right: 10px;">Nueva sub-categoría: </label>
                </div>
                <div class="col-md-2">
                    <input type="text" required maxlength="60" placeholder="Sub-Categoría..." name="nombre_subcategoria" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" id="nombre_subcategoria">
                </div>
                <div class="row">
                    <div class="col-md-2">
                            <label for="categoria" style="margin-left: 10px;margin-right: 10px;">Pertenece a: </label>
                    </div>
                    <div class="col-md-2">
                            <select name="categoria" id="categoria" style="margin-left: 10px; margin-bottom: 5px; width: 100%; max-width: 100%;">
                                    @foreach($categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                                    @endforeach
                            </select>
                    </div>
                    <div class="col-md-2">
                            <input type="submit" value="Crear" class="btn btn-sm btn-success" style="margin-left: 10px;">
                    </div>
                </div> 
            </div>
            @include('themes.lte.partials.toastr')
        </form>
    </div>
        <div class="container-fluid">
                {{-- @foreach($list_subcategorias as $subcategoria)
                <div class="col-md-3">
                        <li style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">{{$subcategoria->name}}</li>
                </div>
                             

                @endforeach --}}
                <div class="panel-body">
                        @foreach($array_cat_subcat as $array)
                        <div class="panel-group" id="accordion{{$array['id_cat']}}" role="tablist" aria-multiselectable="true">
                               <div class="panel panel-default">
                                 <div class="panel-heading" role="tab" id="accordion{{$array['id_cat']}}">
                                   <div class="row">
                                     <div class="col-md-12">
                                         <h4 class="panel-title" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                             
                                             <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion{{$array['id_cat']}}" href="#{{ str_replace(' ' , '' , $array['nombre_cat'])}}" aria-expanded="false" aria-controls="{{ str_replace(' ' , '' , $array['nombre_cat'])}}">
                                                 <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>    
                                               Categoría: {{$array['nombre_cat']}}
                                             </a>
                                         </h4>
                                     </div>
                                   </div>
                                   
                                 </div>
                                 <div id="{{ str_replace(' ' , '' , $array['nombre_cat'])}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$array['id_cat']}}">
                                   <div class="panel-body">
                                           @foreach($array['subcategorias'] as $subcategoria)
                                           @foreach($subcategoria as $val)
                                           <div class="panel-group" id="accordion_sub{{$val['id_subcat']}}" role="tablist" aria-multiselectable="true">
                                   <div class="panel panel-default">
                                   <div class="panel-heading" role="tab" id="{{$val['id_subcat']}}">
                                       <div class="row">
                                       <div class="col-md-12">
                                           <h4 class="panel-title" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                               
                                               <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion_sub{{$val['id_subcat']}}" href="#{{ str_replace( ' ' , '' , $val['name_subcat'] ) }}" aria-expanded="false" aria-controls="{{ str_replace( ' ' , '' , $val['name_subcat'] ) }}">
                                                   <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>     
                                                 Subcategoría: {{$val['name_subcat']}}
                                               </a>
                                               <a class="btn btn-xs btn-link btn-warning" style="text-decoration: none;color:#fff;" href="{{route('editSubcategoria', ['id'=>$val['id_subcat']])}}">Modificar</a>
                                                @if(  count($val['permisos']) == 0 )
                                                <button class="btn btn-xs btn-danger" onclick="eliminarSub({{$val['id_subcat']}}, '{{$val['name_subcat']}}')" style=" margin-left: 5px;" href="#">Eliminar</button>
                                                @endif
                                            </h4>
                                       </div>
                                       </div>
                                       
                                   </div>
                                   <div id="{{ str_replace( ' ' , '' , $val['name_subcat'] ) }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$val['id_subcat']}}">
                                       <div class="panel-body">
                                           
                                          @foreach($val['permisos'] as $permi)
                                          <div class="col-md-3" style="overflow: hidden; text-overflow:ellipsis; white-space:nowrap;">
                                          <li>{{$permi->name}}</li>
                                          </div>
                                          @endforeach
                                                    
                                       </div>
                                   </div>
                                   </div>
                               </div>
                                           @endforeach
                                       @endforeach   
                                   </div>
                                 </div>
                               </div>
                             </div>
                        @endforeach
                   </div>
        </div>
</div>
@endsection

@section('jscripts')
 <script>
   function eliminarSub(id, nombre){
    console.log(id);
    console.log( nombre );
    var alerta = confirm("Desea eliminar la sub-categoría: " + nombre + "?");
    if(alerta){
      $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });

                    $.ajax({

    type:'POST',

    url:'/eliminarSubcategoria',

    data: {subcatid:id},

    success:function(data){
      console.log(data);
      console.log(data.flag);
    if(data.flag){
      alert('La sub-categoría se eliminó satisfactoriamente');
      location.reload();
    }

    }

    });
        }
      }
 </script>
@endsection