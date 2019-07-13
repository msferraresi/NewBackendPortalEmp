<?php

namespace arsatapi\Http\Controllers;
use arsatapi\User;
use Illuminate\Http\Request;
class profileController extends Controller
{
    public function obtenerUsuario($user){
    $perf = User::where('email', $user)->get();
    return $perf;
    }
}
