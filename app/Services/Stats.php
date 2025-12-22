<?php

namespace App\Services;

class Stats
{

    public function parseAndSaveMarketShareData(array $data, string $model, int $chunkSize = 1000): void
    {
        if (!empty($data)) {
            foreach (array_chunk($data, $chunkSize) as $chunk) {
                $now = now();
                $chunk = array_map(function ($c) use ($now) {
                    $c['created_at'] = $now;
                    $c['updated_at'] = $now;
                    return $c;
                }, $chunk);
                $model::query()->insert($chunk);
            }
        }
    }
}
