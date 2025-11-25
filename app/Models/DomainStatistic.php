<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainStatistic extends Model
{
    protected $fillable = [
        'data_length_in_bytes',
        'data_extraction_duration_in_seconds',
        'longest_domain_name',
        'longest_domain_name_length',
        'avg_domain_name_length',
    ];
}
