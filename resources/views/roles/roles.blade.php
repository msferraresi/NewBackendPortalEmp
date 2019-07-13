@extends('themes.lte.layout')
@section('title', '- Roles')
@section('content')
    <div class="container" style="padding: 1%; padding-left:2%; padding-right:2%; width: 98%;">
        <div class="row text-center">
            <h1>Gestión de roles</h1>
            @include('themes.lte.partials.toastr')
        </div>
        <div class="row">
        <form action="{{route('crearRol')}}" method="POST">
          @csrf
          <div class="col-md-6 col-md-offset-3" style="background-color: white; border: 1px solid lightgrey; padding: 1%;">
            <div>
              <label for="rol">Denominación del rol nuevo: </label>
              <input type="text" placeholder="Nombre del rol..." length="60" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+" id="rol" name="rol" required>
              <input type="submit" value="Crear rol" class="btn btn-sm btn-success" style="margin-left: 10px;">
            </div>
          </div>   
          </form>
        </div>
            <div class="container-fluid" style="margin-top: 1%;">
                    @foreach($roles as $rol)
                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                              <div class="panel-heading" role="tab" id="{{$rol['idRol']}}">
                                <div class="row">
                                  <div class="col-md-3">
                                      <h4 class="panel-title" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                          
                                          <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion" href="#{{ str_replace( ' ' , '' ,  $rol['nomRol'] )}}" aria-expanded="false" aria-controls="{{ str_replace( ' ' , '' ,  $rol['nomRol'] )}}">
                                            <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>{{$rol['nomRol']}}
                                          </a>
                                      </h4>
                                  </div>
                                  <div class="col-md-2 col-md-offset-7">
                                  <form action="{{url('/editarRol')}}" method="POST">
                                    @csrf
                                    <button class="btn btn-sm btn-warning" id="btn_rol_{{$rol['idRol']}}" data-id="{{$rol['idRol']}}">Editar rol</button>
                                    <input type="text" value="{{$rol['idRol']}}" id="id_rol" name="id_rol" hidden>
                                  </form>
                                  </div>
                                </div>
                                
                              </div>
                              <div id="{{ str_replace( ' ' , '' ,  $rol['nomRol'] )}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$rol['idRol']}}">
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
@endsection