
<aside class="main-sidebar">

  <section class="sidebar">
      <ul class="sidebar-menu" data-widget="tree">
          @hasrole('super-admin')

          <li class="treeview">
              <a href="#">
                <i class="fa fa-files-o"></i>
                <span>Usuarios</span>
              </a>
              <ul class="treeview-menu">
                  <li>
                      <a href="{{url('usuarios')}}" id="btn-usuarios">
                          <i class="fas fa-users"></i> <span>Usuarios</span>
                      </a>
                    </li>
                  <li>
                      <a href="{{url('permisos')}}" id="btn-roles">
                          <i class="far fa-eye-slash"></i> <span>Permisos</span>
                      </a>
                    </li>
                    <li>
                        <a href="{{url('roles')}}" id="btn-roles">
                            <i class="far fa-eye-slash"></i> <span>Roles</span>
                        </a>
                      </li>
              </ul>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fas fa-ban"></i>
                  <span>Categorías y sub-categorías</span>
                </a>
                <ul class="treeview-menu">
                    <li>
                    <a href="{{url('categorias')}}">
                             <span>Categorías</span>
                        </a>
                      </li>
                      <li>
                          <a href="{{url('subcategorias')}}">
                               <span>Sub-categorías</span>
                          </a>
                        </li>
                </ul>
              </li>
              @endhasrole
      </ul>
  </section>
  <!-- /.sidebar -->
</aside>


