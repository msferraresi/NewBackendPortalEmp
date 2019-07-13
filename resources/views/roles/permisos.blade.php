@extends('themes.lte.layout')
@section('title', '- Permisos')
@section('content')
    <div class="container" style="padding: 1%; padding-left:2%; padding-right:2%; width: 98%;">
        <div class="row text-center">
            <h1>Gestión de permisos</h1>
        </div>
        @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
        
                <h3>Nuevo permiso</h3>
        <form action="{{url('setPermiso')}}" method="POST" id="form-permiso" style="background-color: white; border: 1px solid lightgrey; padding-left: 1%;">
            @csrf
            <div class="row" style="padding-top: 1%; padding-bottom: 1%;">
                <div class="col-md-1">
                        <label for="nombre_permiso">Permiso: </label>
                </div>
                <div class="col-md-4">
                        <input type="text" name="nombre_permiso" id="nombre_permiso" maxlength="60" required
                        value="{{ old('nombre_permiso') }}" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" required
                        style="width:100%; border-radius: 5px; margin-left: 10px; margin-right: 10px;"
                        placeholder="Ingrese el nombre del nuevo permiso...">
                </div>
                <div class="col-md-5">
                        <label for="categoria">Pertenece a: </label>
                        <select name="categoria" id="categoria" style="margin-left: 10px; margin-right: 10px; width:70%;">
                                <option value="0" readOnly>Seleccione una categoria</option>
                                @foreach($list_categorias as $categoria)
                                <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                                @endforeach
                            </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                        <label for="subcategoria">Sub-categoría: </label>
                        <select name="subcategoria" id="subcategoria" style="margin-inline-start: 10px;">
                                <option value="null">Seleccione una sub-categoria</option>
                            </select>
                </div>
                <div class="col-md-1">
                        <input type="submit" value="Crear permiso" class="btn btn-xs btn-success" style="margin-left: 10px;" id="permiso-btn">
                </div>
            </div>
                    @include('themes.lte.partials.toastr')

            </form>
        </div>
            <div class="panel-body">
                    @foreach($array_cat_subcat as $array)
                    <div class="panel-group" id="accordion{{$array['id_cat']}}" role="tablist" aria-multiselectable="true">
                           <div class="panel panel-default">
                             <div class="panel-heading" role="tab" id="accordion{{$array['id_cat']}}">
                               <div class="row">
                                 <div class="col-md-12">
                                     <h4 class="panel-title" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                         <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion{{$array['id_cat']}}" href="#{{ str_replace(' ', '', $array['nombre_cat'])}}" aria-expanded="false" aria-controls="{{ str_replace(' ', '', $array['nombre_cat'])}}">
                                                <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>   
                                            Categoría: {{$array['nombre_cat']}}
                                         </a>
                                     </h4>
                                 </div>
                               </div>
                               
                             </div>
                             <div id="{{ str_replace(' ', '', $array['nombre_cat'])}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="accordion{{$array['id_cat']}}">
                               <div class="panel-body">
                                       @foreach($array['subcategorias'] as $subcategoria)
                                       @foreach($subcategoria as $val)
                                       <div class="panel-group" id="accordion_sub{{$val['id_subcat']}}" role="tablist" aria-multiselectable="true">
                               <div class="panel panel-default">
                               <div class="panel-heading" role="tab" id="{{$val['id_subcat']}}">
                                   <div class="row">
                                   <div class="col-md-12">
                                       <h4 class="panel-title" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                            
                                           <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion_sub{{$val['id_subcat']}}" href="#{{ str_replace(' ', '', $val['name_subcat'] ) }}" aria-expanded="false" aria-controls="{{ str_replace(' ', '', $val['name_subcat'] ) }}">
                                                <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>      
                                            Subcategoría: {{$val['name_subcat']}}
                                           </a>
                                       </h4>
                                   </div>
                                   </div>
                                   
                               </div>
                               <div id="{{ str_replace(' ', '', $val['name_subcat'] ) }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$val['id_subcat']}}">
                                   <div class="panel-body">
                                         @foreach($val['permisos'] as $permiso)
                                             <div class="col-md-3">
                                                 <li>{{$permiso->name}} </li>
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
@endsection

@section('jscripts')
<script type="text/javascript">

$(document).ready(function() {
    $('#permiso-btn').hide();
});

    $.ajaxSetup({

        headers: {

            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

        }

    });
 
    $("#categoria").change(
        function(e){
            e.preventDefault();
            console.log('entra al post de ajax');
            console.log( $(this).val() );
            console.log( $("#categoria option:selected").html());
                            $.ajax({

                type:'POST',

                url:'/getsubcategorias',

                data: {categoria:$(this).val()},

                success:function(data){
                    console.log(data);
                    console.log(data.length);
                    $("#subcategoria").empty();
                if(data){    
                    $('#subcategoria').append("<option value='0' readOnly>Seleccione una sub-categoria</option>");
                    $.each(data,function(id,value){
                        $('#subcategoria').append($("<option/>", {
                           value: this.id,
                           text: this.name
                        }));
                    });
                    
                    $('#permiso-btn').show();
                    
                }

                }

                });
        }
    );

</script>
@endsection