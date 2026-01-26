<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'telefono',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdministrador(): bool
    {
        return $this->role === 'administrador';
    }

    /**
     * Verificar si el usuario es trabajador
     */
    public function isTrabajador(): bool
    {
        return $this->role === 'trabajador';
    }

    /**
     * Verificar si el usuario es cliente
     */
    public function isCliente(): bool
    {
        return $this->role === 'cliente';
    }

    /**
     * Verificar si el usuario tiene un rol especifico
     * 
     * @param string $role
     * @return bool
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Relación con pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }
}
