@extends('themes.lte.layout')
@section('title', '- Categorias')
@section('content')
<div class="container-fluid">
<div class="row text-center">
        <h1>Gestión de categorías</h1>
    </div>
    <div class="row">
    <form action="{{url('setcategorias')}}" method="POST" class="form-group">
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
              <div class="col-md-4 col-md-offset-4" style="background-color: white; border: 1px solid lightgrey; padding-top: 1%; padding-bottom: 1%;">
                  <label for="nombre_categoria" style="margin-right: 10px;">Nueva categoría: </label>
                  <input type="text" required maxlength="60" placeholder="Categoría..."
                  name="nombre_categoria" id="nombre_categoria" pattern="[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]+">
                  <input type="submit" value="Crear" class="btn btn-sm btn-success" style="margin-left: 10px;">
              </div>
            </div>
            @include('themes.lte.partials.toastr')
        </form>
    </div>
        <div class="container-fluid">
                @foreach($array_cat_subcat as $categoria)
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                        <div class="panel panel-default">
                          <div class="panel-heading" style="display: flex; padding-left: 1%;padding-right: 1%;" role="tab" id="{{$categoria['nombre_cat']}}">
                            <h4 class="panel-title" style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">
                                
                              <a role="button" class="collapsed fas-rot" data-toggle="collapse" data-parent="#accordion" href="#{{$categoria['id_cat']}}" aria-expanded="false" aria-controls="{{$categoria['id_cat']}}">
                              <i class="fas fa-chevron-circle-down rotate" style="color: #3C91D4;"></i>
                                    {{$categoria['nombre_cat']}}
                              </a>
                            </h4>
                          <a class="btn btn-xs btn-link btn-warning" style="text-decoration: none;color:#fff;" href="{{route('editCategoria' , ['id' => $categoria['id_cat']])}}">Modificar</a>
                          @if(  count($categoria['subcategorias']) == 0 )
                          <button class="btn btn-xs btn-danger" style="margin-left: 5px;" onclick="eliminarCategoria( {{$categoria['id_cat']}}, '{{$categoria['nombre_cat']}}' )" id="btn-del-cat">Eliminar</button>
                          @endif
                          </div>
                          <div id="{{$categoria['id_cat']}}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{$categoria['id_cat']}}">
                            <div class="panel-body">
                                    @foreach($categoria['subcategorias'] as $subs)
                                    @foreach($subs as $val)
                                    <div class="col-md-3">
                                    <li style="text-overflow:ellipsis; overflow: hidden; white-space:nowrap;">{{$val['name_subcat']}}</li>
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
 <script>
    function eliminarCategoria(id, nombre){
    var alerta = confirm("Desea eliminar la categoría: " + nombre + "?");
    if(alerta){
      $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                    });

                    $.ajax({

    type:'POST',

    url:'/eliminarCategoria',

    data: {catid:id},

    success:function(data){
    if(data.flag){
      alert('La categoría se eliminó satisfactoriamente');
      location.reload();
    }

    }

    });
        }
      }
 </script>
@endsection