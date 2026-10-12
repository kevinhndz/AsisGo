<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;
    protected $guarded = [];
    protected $hidden = ['contrasena'];

    public function getAuthIdentifierName(): string
    {
        return 'id_usuario';
    }

    public function getAuthPassword(): string
    {
        return $this->contrasena;
    }
}
