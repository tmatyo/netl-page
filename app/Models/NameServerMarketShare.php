<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NameServerMarketShare extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'ns',
        'domain_count',
    ];

}
