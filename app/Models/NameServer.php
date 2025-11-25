<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Domain;

class NameServer extends Model
{
    protected $fillable = [
        'name_server',
    ];

    public function domains()
    {
        return $this->belongsToMany(Domain::class, 'domain_name_servers', 'name_server_id', 'domain_id');
    }
}
