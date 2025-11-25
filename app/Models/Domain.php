<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Registrar;
use App\Models\Owner;
use App\Models\ExpiringDomain;
use App\Models\NameServer;

class Domain extends Model
{
    protected $fillable = [
        'domain',
        'registrar_id',
        'owner_id',
        'expiry_date',
    ];

    public function registrar()
    {
        return $this->belongsTo(Registrar::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function nameServers()
    {
        return $this->hasMany(NameServer::class);
    }

    public function expiringDomain()
    {
        return $this->hasOne(ExpiringDomain::class);
    }

}
