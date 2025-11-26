<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarHeatmapByDay extends Model
{
    protected $fillable = [
        'expiry_date',
        'domain_count',
    ];
}
