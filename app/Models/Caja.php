<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;


    protected $fillable = [
        'apertura',
        'cierre',
        'capital_inicial',
        'capital_final',
        'capital_prestado',
        'refuerzo',
    ];
}
