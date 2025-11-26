<?php

namespace App\Http\Controllers;

use App\Models\CalendarHeatmapByDay;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\DomainStatistic;
use App\Models\Crawling;

class ApiController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'API is working', 'ok' => true]);
    }

    public function import(Request $request)
    {
        $data = $request->json()->all();
        DB::transaction(function () use ($data) {

            # Create and save DomainStatistic
            $domainStatistic = new DomainStatistic([
                'data_length_in_bytes' => $data['data_length_in_bytes'] ?? null,
                'data_extraction_duration_in_seconds' => $data['data_extraction_duration_in_seconds'] ?? null,
                'longest_domain_name' => $data['longest_domain_name'] ?? null,
                'longest_domain_name_length' => $data['longest_domain_name_length'] ?? null,
                'avg_domain_name_length' => $data['avg_domain_name_length'] ?? null,
            ]);
            $domainStatistic->save();

            # Process loads_info
            if (isset($data['loads_info'], $data['loads_info']['latest_load'], $data['loads_info']['previous_load'])) {
                $latestLoad = $data['loads_info']['latest_load'];
                $previousLoad = $data['loads_info']['previous_load'];

                $crawlingPrevious = new Crawling($previousLoad);
                $crawlingPrevious->save();
                $crawlingLatest = new Crawling($latestLoad);
                $crawlingLatest->save();
            }

            if (!empty($data['calendar_heatmap_by_day'])) {
                foreach (array_chunk($data['calendar_heatmap_by_day'], 1000) as $chunk) {
                    $now = now();
                    $chunk = array_map(function ($c) use ($now) {
                        $c['created_at'] = $now;
                        $c['updated_at'] = $now;
                        return $c;
                    }, $chunk);
                    CalendarHeatmapByDay::query()->insert($chunk);
                }
            }
        });

        return response()->json([
            'imported_data' => 123,
            'ok' => true
        ]);
    }
}
