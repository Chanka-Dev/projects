<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan_cuenta extends Model
{
    use HasFactory;

    protected $table = 'plan_cuentas';

    protected $fillable = [
        'codigo',
        'nombre',
        'tipo_cuenta',
        'subtipo',
        'nivel',
        'cuenta_padre_id',
        'acepta_movimientos',
        'naturaleza',
        'activa',
    ];

    protected $casts = [
        'acepta_movimientos' => 'boolean',
        'activa' => 'boolean',
    ];

    // Relaciones
    public function cuentaPadre()
    {
        return $this->belongsTo(Plan_cuenta::class, 'cuenta_padre_id');
    }

    public function cuentasHijas()
    {
        return $this->hasMany(Plan_cuenta::class, 'cuenta_padre_id');
    }

    public function detalleAsientos()
    {
        return $this->hasMany(Detalle_asiento::class, 'cuenta_id');
    }

    public function libros()
    {
        return $this->hasMany(Libro::class, 'cuenta_id');
    }

    public function productosInventario()
    {
        return $this->hasMany(Producto::class, 'cuenta_inventario_id');
    }

    public function productosCosto()
    {
        return $this->hasMany(Producto::class, 'cuenta_costo_venta_id');
    }

    public function productosIngreso()
    {
        return $this->hasMany(Producto::class, 'cuenta_ingreso_id');
    }

    public function categoriasGasto()
    {
        return $this->hasMany(Categoria_gasto::class, 'cuenta_id');
    }

    public function gastosOperativos()
    {
        return $this->hasMany(Gasto_operativo::class, 'cuenta_id');
    }

    // Scopes
    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeAceptaMovimientos($query)
    {
        return $query->where('acepta_movimientos', true);
    }

    public function scopeTipo($query, $tipo)
    {
        return $query->where('tipo_cuenta', $tipo);
    }

    public function scopeNivel($query, $nivel)
    {
        return $query->where('nivel', $nivel);
    }

    // Métodos auxiliares
    public function esDeudora()
    {
        return $this->naturaleza === 'deudora';
    }

    public function esAcreedora()
    {
        return $this->naturaleza === 'acreedora';
    }

    public function saldoActual($periodo = null)
    {
        if ($periodo) {
            $libro = $this->libros()->where('periodo', $periodo)->first();
            return $libro ? $libro->saldo_final : 0;
        }

        $debe = $this->detalleAsientos()->sum('debe');
        $haber = $this->detalleAsientos()->sum('haber');

        if ($this->esDeudora()) {
            return $debe - $haber;
        }

        return $haber - $debe;
    }

    public function codigoCompleto()
    {
        return $this->codigo . ' - ' . $this->nombre;
    }

    /**
     * Calcula el saldo de la cuenta hasta una fecha de corte
     * 
     * @param string|null $fechaCorte
     * @return float
     */
    public function calcularSaldo($fechaCorte = null)
    {
        $query = $this->detalleAsientos()
            ->whereHas('asiento', function ($q) use ($fechaCorte) {
                $q->where('estado', 'contabilizado');
                if ($fechaCorte) {
                    $q->where('fecha', '<=', $fechaCorte);
                }
            });

        $debe = $query->sum('debe');
        $haber = $query->sum('haber');

        // Retornar según naturaleza de la cuenta
        if ($this->esDeudora()) {
            return $debe - $haber;
        }

        return $haber - $debe;
    }

    /**
     * Calcula el saldo de la cuenta en un período específico
     * 
     * @param string $fechaDesde
     * @param string $fechaHasta
     * @return float
     */
    public function calcularSaldoPeriodo($fechaDesde, $fechaHasta)
    {
        $query = $this->detalleAsientos()
            ->whereHas('asiento', function ($q) use ($fechaDesde, $fechaHasta) {
                $q->where('estado', 'contabilizado')
                  ->whereBetween('fecha', [$fechaDesde, $fechaHasta]);
            });

        $debe = $query->sum('debe');
        $haber = $query->sum('haber');

        // Para cuentas de resultado, retornar el movimiento neto del período
        if ($this->esDeudora()) {
            return $debe - $haber;
        }

        return $haber - $debe;
    }
}
