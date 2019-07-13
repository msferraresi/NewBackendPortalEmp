<?php

namespace arsatapi;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table    = 'files';
    protected $fillable = ['path','month','year', 'id_typeFile'];
    protected $guarded  = ['id'];
    protected static $logAttributes = ['path','month','year'];
}
