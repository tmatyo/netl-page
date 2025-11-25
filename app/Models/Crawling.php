<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crawling extends Model
{
    protected $fillable = [
        'table_name',
        'time_generated',
        'crawling_duration',
        'download_duration',
        'avg_speed_in_bytes_per_sec',
        'file_size',
        'domain_count',
    ];

    public function metrics()
    {
        return $this->hasMany(Metric::class, 'crawling_id');
    }
}
