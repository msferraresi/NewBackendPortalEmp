<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Tool Support ARSAT @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{asset("assets/lte/bower_components/bootstrap/dist/css/bootstrap.min.css")}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset("assets/lte/bower_components/font-awesome/css/font-awesome.min.css")}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{asset("assets/lte/bower_components/Ionicons/css/ionicons.min.css")}}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{asset("assets/lte/bower_components/jvectormap/jquery-jvectormap.css")}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset("assets/lte/dist/css/AdminLTE.min.css")}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{asset("assets/lte/dist/css/skins/_all-skins.min.css")}}">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
  <link rel="stylesheet" href="{{asset("css/toaster.min.css")}}">
  <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{asset("css/estilos.css")}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini text-inner">
<div class="wrapper">

  {{-- header  --}}
  @include('themes.lte.header')
  {{-- fin header --}}

  {{-- aside  --}}
  @include('themes.lte.aside')
  {{-- fin aside --}}

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      @yield('content')
      @isset($mensaje)
        <div class="alert alert-success">
            <ul>
                <li>{{$mensaje}}</li>
            </ul>
        </div>
      @endisset
  </div>
  <!-- /.content-wrapper -->

  {{-- footer  --}}
  @include('themes.lte.footer')
  {{-- fin footer --}}


</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="{{asset("assets/lte/bower_components/jquery/dist/jquery.min.js")}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset("assets/lte/bower_components/bootstrap/dist/js/bootstrap.min.js")}}"></script>
<!-- FastClick -->
<script src="{{asset("assets/lte/bower_components/fastclick/lib/fastclick.js")}}"></script>
<!-- AdminLTE App -->
<script src="{{asset("assets/lte/dist/js/adminlte.min.js")}}"></script>
@toastr_js
@toastr_render
<script>
    $("#btn-usuarios").click(function() {
            $.ajax({
                type: 'POST', 
                url : "/getUsuarioMenu", 
                success : function (data) {
                    $("#body-content").html(data);
                }
            });
        });
    
        $(document).ready( function() {
        $('#toast-container').delay(1000).fadeOut();
      });

      $(".fas-rot").click(function(){
    $(this).children().toggleClass("down"); 
});
    </script>

    <script>
    var url = window.location;
        $('ul.sidebar-menu a').filter(function() {
            return this.href == url;
        }).parent().siblings().removeClass('active').end().addClass('active');
        $('ul.treeview-menu a').filter(function() {
            return this.href == url;
        }).parentsUntil(".sidebar-menu > .treeview-menu").siblings().removeClass('active menu-open').end().addClass('active menu-open');
    </script>

    @yield('jscripts')  
</body>
</html>
