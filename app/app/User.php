<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use App\Models\Dependencia;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'activo', 'google_id'
    ];
                                                                                                                                
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function dependencias()
    {
        return $this->belongsToMany(Dependencia::class, 'usuarios_dependencias', 'usuario_id', 'dependencia_id');
    }

    public function dependencias_origen()
    {
        return $this->belongsToMany(Dependencia::class, 'usuario_dependencia_origen', 'usuario_id', 'dependencia_id');
    }
}
