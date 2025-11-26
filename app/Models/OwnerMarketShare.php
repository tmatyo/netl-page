<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class OwnerMarketShare extends Model
{
    protected $fillable = [
        'owner',
        'domain_count',
        'percentage',
    ];

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
