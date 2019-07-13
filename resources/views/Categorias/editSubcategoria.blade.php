@extends('themes.lte.layout')
@section('title', '- Sub-Categorias')
@section('content')
<div class="container-fluid">
        @include('themes.lte.partials.toastr')
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <div class="row">
        <div class="col-md-12 text-center">
        <h3>Editar nombre de la sub-categoría: {{$subcat->name}}</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <form action="{{route('editarSubcategoria', ['subcat'=>$subcat])}}" method="POST">
            @csrf
        <input type="text" class="form-control" name="nombreSubcat" id="nombreSubcat" value="{{$subcat->name}}">
            <input type="submit" value="Modificar" class="btn btn-sm btn-primary" style="margin-top: 5px;">
        </form>
        </div>
    </div>
</div>

@endsection