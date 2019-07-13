@extends('themes.lte.layout')
@section('title', '- Usuarios')
@section('content')
    <div class="container-fluid">
            <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"><a href="#permisos" aria-controls="permisos" role="tab" data-toggle="tab">Permisos</a></li>
                    <li role="presentation"><a href="#roles" aria-controls="roles" role="tab" data-toggle="tab">Roles</a></li>
            </ul>
          
            <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="permisos">
                    @include('themes.lte.partials.toastr')
                            <form action="{{route('modificarPermisosUsuario')}}" method="POST">
                                    {{Session::put('usuario', $usuario)}}
                                    {{Session::save()}}
                                    @csrf
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                            <div class="panel panel-default">
                                              <div class="panel-heading" role="tab">
                                                <div class="row">
                                                  <div class="col-md-12">
                                                      <h4 class="panel-title" style="font-weight: 900;">
                                                          Editando el Usuario {{$usuario->name ." ". $usuario->lastname ." || ID: ". $usuario->employeeid."."}}
                                                      </h4>
                                                      @if(Session::has('exito'))
                                                      <span style="font-weight: 900; color: green;">{{Session::get('exito')}}</span>
                                                      @endif
                                                  </div>
                                                </div>
                                              </div>
                                              <div>
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
                                                                        </h4>
                                                                    </div>
                                                                    </div>
                                                                    
                                                                </div>
                                                                <div id="{{ str_replace( ' ' , '' , $val['name_subcat'] ) }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$val['id_subcat']}}">
                                                                    <div class="panel-body">
                                                                        
                                                                        @foreach($val['permisos'] as $permiso)
                                                                              <div class="col-md-3" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                                                                  <input type="checkbox" name="checkbox[]"
                                                                                  data-checkbox="checkbox"
                                                                        @if( count(array_pluck($permisos_usuario, 'value')) > 0)
                                                                        
                                                                            @foreach($permisos_usuario as $permiUsr)
                                                                            
                                                                              @if($permiso->name == $permiUsr['name'])
                                                                                  checked
                                                                              @endif
                                                                              
                                                                            @endforeach
                                                                            
                                                                        @endif
                                                                        value="{{$permiso->id}}"> 
                                                                        <strong>{{$permiso->name}} </strong> <br>
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
                                            <input type="submit" value="Modificar Permisos" class="btn btn-block btn-primary">
                                          </div>
                                    </form>

                    </div>

                    <div role="tabpanel" class="tab-pane" id="roles">
                            @foreach($roles as $rol)
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                    <div class="panel panel-default">
                                      <div class="panel-heading" role="tab" id="{{$rol['idRol']}}">
                                        <div class="row">
                                          <div class="col-md-3">
                                              <h4 class="panel-title" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                                  
                                                  <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion" href="#{{str_replace(' ' , '' , $rol['nomRol'] ) }}" aria-expanded="false" aria-controls="{{str_replace(' ' , '' , $rol['nomRol'] ) }}">
                                                      <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>    
                                                    {{$rol['nomRol']}}
                                                  </a>
                                              </h4>
                                          </div>
                                          <div class="col-md-2 col-md-offset-7">
                                          <form action="#" method="POST">
                                            @csrf
                                            @if( in_array( $rol['nomRol'] , $usuario->roles->toArray() ) )
                                          <button class="btn btn-sm btn-danger btn-quitar" id="btn_rol_{{$rol['idRol']}}" data-id="{{$rol['idRol']}}" data-usr="{{$usuario->id}}">Quitar Rol</button> 
                                            @else
                                            <button class="btn btn-sm btn-primary btn-asignar" id="btn_rol_{{$rol['idRol']}}" data-id="{{$rol['idRol']}}" data-usr="{{$usuario->id}}">Asignar Rol</button>
                                            @endif
                                            
                                            <input type="text" value="{{$rol['idRol']}}" id="id_rol" name="id_rol" hidden>
                                          </form>
                                          </div>
                                        </div>
                                        
                                      </div>
                                      <div id="{{str_replace(' ' , '' , $rol['nomRol'] ) }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$rol['idRol']}}">
                                        <div class="panel-body">
                                                @foreach($rol['permisos'] as $permiso)
                                                @foreach($permiso as $val)
                                                <div class="col-md-3" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                                <li>{{$val['permiso']}}</li>
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
    $('.btn-asignar').click(function(e){

      e.preventDefault();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

                    $.ajax({

                    type:'POST',

                    url:'/asignarRol',
                    
                    data: {rolid: $(this).attr('data-id'), usrid:$(this).attr('data-usr')  },

                    success:function(data){
                    if(data == 'ok'){
                      location.reload();
                    }

                    }

                    });

    });

    $('.btn-quitar').click(function(e){
      e.preventDefault();
      console.log('quitar rol');
      console.log( 'Id de rol: ' + $(this).attr('data-id') );
      console.log( 'Id de usuario:' + $(this).attr('data-usr') );

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

                    $.ajax({

                    type:'POST',

                    url:'/quitarRol',
                    
                    data: {rolid: $(this).attr('data-id'), usrid:$(this).attr('data-usr')  },

                    success:function(data){
                        console.log( data );
                    if(data){
                      location.reload();
                    }

                    }

                    });

    });
    </script>
  
      
@endsection