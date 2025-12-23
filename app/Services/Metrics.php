<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Exception;
use App\Models\Metric;

class Metrics
{

    public function saveMetric(array $metricData, string $metric_type): void
    {
        $metrics = [];
        foreach ($metricData as $data) {
            $metrics[] = [
                'metric_type' => $metric_type ?? null,
                'date' => $data['date'] ?? null,
                'value' => $data['value'] ?? null,
            ];
        };

        if (empty($metrics)) {
            Log::error("METRICS: No metrics to process.");
            return;
        }

        try {
            Metric::insert($metrics);
        } catch (Exception $e) {
            Log::error("METRICS: Error saving metrics. " . $e->getMessage());
        }
    }
}
