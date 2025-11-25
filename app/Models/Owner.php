<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Domain;

class Owner extends Model
{
    protected $fillable = [
        'name',
    ];

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }
}
