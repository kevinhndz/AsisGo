<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grabacion extends Model
{
    protected $table = 'grabaciones';
    protected $primaryKey = 'id_grabacion';
    public $timestamps = false;
    protected $guarded = [];
}
