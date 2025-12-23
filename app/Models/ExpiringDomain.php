<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpiringDomain extends Model
{
    protected $fillable = [
        'domain',
        'expiry_date',
    ];

}
