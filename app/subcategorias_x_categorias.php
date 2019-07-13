<?php

namespace arsatapi;

use Illuminate\Database\Eloquent\Model;

class subcategorias_x_categorias extends Model
{
    protected $table = 'subcategorias_x_categorias';
    protected $fillable = [
        'id_cat',
        'id_subcat',
    ];
    protected static $logAttributes = ['id_cat', 'id_subcat'];
}
