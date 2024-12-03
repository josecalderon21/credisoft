<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombres',
        'apellidos',
        'tipo_documento',
        'numero_documento',
        'telefono',
        'ciudad',
        'direccion',
        'email',
        'codeudor_nombres',
        'codeudor_apellidos',
        'codeudor_tipo_documento',
        'codeudor_numero_documento',
        'codeudor_telefono',
        'codeudor_ciudad',
        'codeudor_direccion',
        'codeudor_email',
        'archivo',
    ];


  // Concatenar nombre y apellido
  public function getFullNameAttribute()
  {
      return $this->nombres . ' ' . $this->apellidos;
  }
// Cliente.php
public function prestamos()
{
    return $this->hasMany(Prestamo::class);
}

// Relación con estado de deuda
public function estadoDeDeuda()
{
    return $this->hasMany(EstadoDeDeuda::class, 'prestamo_id');
}

// Cliente.php
public function pagos()
{
    return $this->hasMany(Pago::class);
}

}
