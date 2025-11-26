<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistrarMarketShare extends Model
{
    protected $fillable = [
        'registrar',
        'domain_count',
        'percentage',
    ];

    public function registrar()
    {
        return $this->belongsTo(Registrar::class);
    }
}
