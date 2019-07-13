<?php

namespace arsatapi;

use Illuminate\Database\Eloquent\Model;

class TypeFile extends Model
{
    protected $table    = 'type_files';
    protected $fillable = ['description'];
    protected $guarded  = ['id'];
    protected static $logAttributes = ['description'];
}
