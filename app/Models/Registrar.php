<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Domain;

class registrar extends Model
{
    protected $fillable = [
        'name',
    ];

    public function domains()
    {
        return $this->hasMany(Domain::class);
    }

}
