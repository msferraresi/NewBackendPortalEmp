<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login | Tool Suppor Arsat</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset("css/app.css")}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body style="height: 100vh;">
<div class="d-flex align-items-center" style="height: 100%;">
    <div class="col-md-6 offset-md-3">
        <div class="panel panel-default" style="padding: 5%; border: 1px solid lightgrey; border-radius: 10px;">
            <div class="panel-heading">
                <h1>Login TSA</h1>
            </div>
            <div class="panel-body">
            <form action="{{url('/loginAttempt')}}" method="POST">
                @csrf
                <div class='form-group'>
                        <label for="usuario">Usuario</label>
                        <input class="form-control" type="text" name="username" id="username" placeholder="Ingresa tu usuario...">
                    </div>
                    <div class="form-group">
                        <label for="usuario">Contraseña</label>
                        <input class="form-control" type="password" name="password" id="password" placeholder="Ingresa tu contraseña...">
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
                    <button class="btn btn-primary btn-block">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{asset("js/app.js")}}"></script>

</body>
</html>