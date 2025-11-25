<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Crawling;

class Metric extends Model
{
    protected $fillable = [
        'crawling_id',
        'metric_type',
        'date',
        'value',
    ];

    public function crawling()
    {
        return $this->belongsTo(Crawling::class);
    }
}
