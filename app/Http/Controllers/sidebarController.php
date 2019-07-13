<?php

namespace arsatapi\Http\Controllers;

use Illuminate\Http\Request;

class sidebarController extends Controller
{
    public function getUsuarioMenu(){
        return view('usuarios.usuarios');
    }
}
