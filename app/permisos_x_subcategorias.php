<?php

namespace arsatapi;

use Illuminate\Database\Eloquent\Model;

class permisos_x_subcategorias extends Model
{
    protected $fillable = [ 'id_per', 'id_subcat' ];
    protected static $logAttributes = ['id_per', 'id_subcat'];
}
