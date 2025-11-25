<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Domain;

class ExpiringDomain extends Model
{
    protected $fillable = [
        'domain_id',
        'expiry_date',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }
}
