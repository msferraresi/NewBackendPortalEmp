<?php

namespace arsatapi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class subcategoria extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'name',
    ];
    protected static $logAttributes = ['name'];
}
