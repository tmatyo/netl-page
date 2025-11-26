<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NameServerMarketShare extends Model
{
    protected $fillable = [
        'ns',
        'domain_count',
    ];

    public function nameServer()
    {
        return $this->belongsTo(NameServer::class);
    }
}
