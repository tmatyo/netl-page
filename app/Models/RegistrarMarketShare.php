<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistrarMarketShare extends Model
{
    use HasFactory;
    protected $fillable = [
        'registrar',
        'domain_count',
        'percentage',
    ];

}
