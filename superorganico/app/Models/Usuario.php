<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Usuario extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relaciones
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'usuario_id');
    }

    public function compras()
    {
        return $this->hasMany(Compra::class, 'usuario_id');
    }

    public function asientosContables()
    {
        return $this->hasMany(Asiento_contable::class, 'usuario_id');
    }

    public function gastosOperativos()
    {
        return $this->hasMany(Gasto_operativo::class, 'usuario_id');
    }

    public function movimientosInventario()
    {
        return $this->hasMany(Movimiento_inventario::class, 'usuario_id');
    }

    // Scopes
    public function scopeAdministradores($query)
    {
        return $query->where('rol', 'administrador');
    }

    public function scopeEmpleados($query)
    {
        return $query->where('rol', 'empleado');
    }

    // Métodos auxiliares
    public function esAdministrador()
    {
        return $this->rol === 'administrador';
    }

    public function esEmpleado()
    {
        return $this->rol === 'empleado';
    }
    
    public function tienePermiso($permiso)
    {
        // Administrador tiene todos los permisos
        if ($this->esAdministrador()) {
            return true;
        }
        
        // Permisos de empleado
        $permisosEmpleado = [
            'ventas.create',
            'ventas.index',
            'inventario.index',
            'clientes.create',
            'clientes.index',
            'dashboard.index',
        ];
        
        return in_array($permiso, $permisosEmpleado);
    }
}
