
@extends('themes.lte.layout')
@section('title', '- Usuarios')
@section('content')
@include('themes.lte.partials.toastr')
    <div class="container-fluid">
        <div class="row text-center">
            <h2>Usuarios</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-10 col-md-offset-1">
                <div class="card" style="border: 1px solid lightgrey; border-radius:10px; padding: 2%;">
                    <div class="card-header text-center" >
                        Usuarios autenticados en la aplicación
                    </div>
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                    <div class="form-control" style="height: 100%; display: inline-block;">
                    <form action="{{route("buscarusuarios")}}" method="GET">
                        @csrf
                        <label for="busqueda_usr" style="margin-right: 5px; border-radius: 5px;">Usuario a buscar: </label>
                        <input type="text" pattern="[a-zA-Z0-9@\s]+" style="width: 30%; border-radius: 5px;margin-right: 5px;" name="busqueda_usr" id="busqueda_usr">

                                <label for="filtro">Filtro: </label>
                                <select id="filtro" name="filtro">
                                    <option value="todos">Todos los usuarios</option>
                                    <option value="con">Usuarios con permisos</option>
                                    <option value="sin">Usuarios sin permisos</option>
                                    
                                </select>

                        
                        <input type="submit" value="Buscar" class="btn btn-sm btn-primary">
                        
                        </form>
                    </div>
                    </div>
                </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Acciones</th>
                            </thead>
                            <tbody>
                                @foreach($lst_usuarios as $val)
                                @if($val->deleted_at != null)
                                <tr style="background: rgba(123,78,100, .3);">
                                @else  
                                <tr>
                                @endif
                                <td>{{$val->name." ".$val->lastname}}</td>
                                <td>{{$val->email}}</td>
                                <td>
                                 
                                <a href="#" class="btn-modal" data-toggle="modal" data-target="#modal-default" data-idUsr="{{$val->employeeid}}" title="Ver detalle" style="margin-right: 10px; color: #006600;"><i class="fas fa-eye"></i></a>
                                
                                <a href="{{route('editUsr', ['id'=>$val->employeeid])}}" title="Editar permisos"><i class="fas fa-universal-access" style="margin-right: 10px; color: #FF8000;"></i></a>
                                
                                @if($val->deleted_at != null)
                                <a href="{{route('habilitarUsr', ['id'=>$val->employeeid])}}" data-idUsr="{{$val->employeeid}}" title="Habilitar usuario" style="margin-right: 10px; color: #84F786;"><i class="fas fa-user-check"></i></a>
                                @else  
                                
                                <a href="{{route('bloquearUsr', ['id'=>$val->employeeid])}}" data-idUsr="{{$val->employeeid}}" title="Bloquear usuario" style="margin-right: 10px; color: #B40C0C;"><i class="fas fa-minus-circle"></i></a>
                                @endif
                            </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        @if( count($lst_usuarios) == 0 )
                            <div class="row text-center">
                                <div class="col-md-12">
                                    <h3>No se han encontrado usuarios con esos parámetros.</h3>
                                </div>
                            </div>
                            @endif
    </div>


          <div class="modal fade text-center" id="modal-default" > 
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="nombre-modal"></h4>
                    </div>
                    <div class="modal-body row">
                      <div class="col-md-12">
                            <div class="row">
                                    <div class="col-md-12">
                                    <p id="bloqueado" style="font-weight:900; color: red;"></p>
                                    </div>
                                </div>
                          <div class="row">
                              <div class="col-md-12">
                              <p id="idempleado-modal"></p>
                              </div>
                          </div>
                          <div class="row">
                                <div class="col-md-12">
                                <p id="posicion-modal"></p>
                                </div>
                            </div>
                            <div class="row">
                                    <div class="col-md-12">
                                    <p id="email-modal"></p>
                                    </div>
                                </div>
                                <div class="row">
                                        <div class="col-md-12">
                                        <p id="creado-modal"></p>
                                        </div>
                                    </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                  <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
              </div>
              <!-- /.modal -->

@endsection

@section('jscripts')
              <script>
                  $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });

                  $('.btn-modal').click(function(e){
            e.preventDefault();
            var idusr = $(this).attr('data-idusr');
                            $.ajax({

                type:'POST',

                url:'/getdetalleusuario',

                data: {userid:idusr},

                success:function(data){
                $('#nombre-modal').html("");
                    $('#bloqueado').html("");
                    $('#idempleado-modal').html("");
                    $('#posicion-modal').html("");
                    $('#email-modal').html("");
                    $('#creado-modal').html("");
                 if(data){
                    $('#nombre-modal').html(data.name + " " + data.lastname);

                    if(data.deleted_at != null){
                        $('#bloqueado').html('Usuario bloqueado');
                    }
                    $('#idempleado-modal').html("ID de empleado: " + data.employeeid);
                    $('#posicion-modal').html( "Posicion: " + data.position);
                    $('#email-modal').html("E-mail: " + data.email);
                    $('#creado-modal').html("Creado: " + data.created_at);
                 }

                }

                });
        });
              </script>
@endsection