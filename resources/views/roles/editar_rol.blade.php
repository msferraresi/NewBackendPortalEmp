@extends('themes.lte.layout')

@section('content')
@section('title', '- Edición de roles')
    <div class="container-fluid" style="padding: 1%;">
        <form action="{{url('/modificarRol')}}" method="POST">
            @csrf
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <div class="panel panel-default">
                      <div class="panel-heading" role="tab">
                        <div class="row">
                          <div class="col-md-12">
                              <h4 class="panel-title" style="font-weight: 900;">
                                  Editando el rol {{$rol->name}}
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
                                              <h4 class="panel-title">
                                                  <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion{{$array['id_cat']}}" href="#{{ str_replace(' ', '', $array['nombre_cat']) }}" aria-expanded="false" aria-controls="{{ str_replace(' ', '', $array['nombre_cat']) }}">
                                                      <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>    
                                                    Categoría: {{$array['nombre_cat']}}
                                                  </a>
                                              </h4>
                                          </div>
                                        </div>
                                        
                                      </div>
                                      <div id="{{ str_replace(' ', '', $array['nombre_cat']) }}" class="panel-collapse collapsed collapse" role="tabpanel" aria-labelledby="{{$array['id_cat']}}">
                                        <div class="panel-body">
                                                @foreach($array['subcategorias'] as $subcategoria)
                                                @foreach($subcategoria as $val)
                                                <div class="panel-group" id="accordion_sub{{$val['id_subcat']}}" role="tablist" aria-multiselectable="true">
                                        <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="{{$val['id_subcat']}}">
                                            <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="panel-title">
                                                    <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion_sub{{$val['id_subcat']}}" href="#{{ str_replace(' ','', $val['name_subcat'] ) }}" aria-expanded="false" aria-controls="{{str_replace(' ','', $val['name_subcat'] ) }}">
                                                        <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>     
                                                      Subcategoría: {{$val['name_subcat']}}
                                                    </a>
                                                </h4>
                                            </div>
                                            </div>
                                            
                                        </div>
                                        <div id="{{str_replace(' ','', $val['name_subcat'] ) }}" class="panel-collapse collapsed collapse" role="tabpanel" aria-labelledby="{{$val['id_subcat']}}">
                                            <div class="panel-body">
                                                {{-- Permisos de cada subcategoria --}}

                                                  @foreach($val['permisos'] as $permiso)
                                                    {{-- Compara con los permisos del rol  si existen permisos para el rol a mostrar.
                                                      Para mostar los checkbox como checked--}}
                                                      <div class="col-md-3">
                                                          <input type="checkbox" name="checkbox[]"
                                                          data-checkbox="checkbox"
                                                    @foreach($permisos_rol as $permiRol)
                                                    
                                                      @if($permiso->name == $permiRol->name)
                                                          checked
                                                      @endif
                                                      
                                                    @endforeach
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
                    <input type="text" value="{{$rol->id}}" hidden name="id_rol" id="id_rol">
                    <input type="submit" value="Modificar Rol" class="btn btn-block btn-primary">
                  </div>
            </form>
    </div>
@endsection