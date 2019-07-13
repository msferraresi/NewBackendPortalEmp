<header class="main-header">

  <!-- Logo -->
  <a href="{{route('home')}}" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>{{env('APP_ABBR')}}</b></span>
    <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><b>{{env('APP_TITLE')}}</b></span>
  </a>


  <nav class="navbar navbar-static-top">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
      <span class="sr-only">Toggle navigation</span>
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        @role('super-admin')
        <li class="dropdown notifications-menu">
        <a href="" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-bell"></i>
            <span class="label label-warning">{{count(config('usr_sinPermisos'))}}</span>
            </a>
            @if( count(config('usr_sinPermisos')) > 0 )
            <ul class="dropdown-menu" style="background: rgba(180, 180, 180, .3);">
                <a href="{{url('usuarios/sin_permisos')}}"> <li style="padding: 2%;"><i class="fas fa-exclamation" style="color: orange;margin-right: 10px;"></i>Hay {{count(config('usr_sinPermisos'))}} usuarios sin permisos.</li></a>
            </ul>
            @endif
          </li>
        @endrole
        <li class="dropdown user user-menu">

          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <span>{{ $usr->name . ' ' . $usr->lastname}}</span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header" style="height: 115px;">

              <p>
                {{ $usr->name . ' ' . $usr->lastname }}

              <small>Creado {{$usr->created_at}}</small>
              <small>Correo {{$usr->email}}</small>
              </p>
            </li>
            <li class="user-footer">
              <div style="display: flex; justify-content:center;">
                <form action="{{route('logout')}}" method="POST">
                    @csrf
                <input type="submit" class="btn btn-default btn-link" value="Salir">
                </form>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>
