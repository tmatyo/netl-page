<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OwnerMarketShare extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'owner',
        'domain_count',
        'percentage',
    ];

}
