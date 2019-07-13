<?php

namespace arsatapi\Http\Controllers;

use Illuminate\Http\Request;
use arsatapi\User;
class inicioController extends Controller
{
    public function index()
    {
        $usr = session()->get('usr');
        $usr = $usr[0];
        activity()->log('function index() user: ' . $usr->email);
        return view('inicio' , compact('usr'));
    }
}
