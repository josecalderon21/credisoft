<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Capital extends Model
{
    protected $table = 'capital'; // Nombre de la tabla si no sigue la convención por defecto

    protected $fillable = ['monto'];

    // Relación con préstamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }

    // Relación con pagos
    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    // Relación con refuerzos
    public function refuerzos()
    {
        return $this->hasMany(RefuerzoCapital::class);
    }
}
