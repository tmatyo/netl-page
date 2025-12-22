<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\DomainStatistic;
use App\Models\Crawling;
use App\Models\CalendarHeatmapByDay;
use App\Models\OwnerMarketShare;
use App\Models\RegistrarMarketShare;
use App\Models\NameServerMarketShare;
use App\Services\DomainSk;
use App\Services\Stats;

class ApiController extends Controller
{
    private const CHUNK_SIZE = 1000;

    public function index()
    {
        return response()->json([
            'load_time' => now()->toDateTimeString(),
            'message' => 'API is working',
            'ok' => true
        ]);
    }

    public function import(Request $request, DomainSk $domainSk, Stats $stats)
    {

        # set higher memory limit and execution time
        ini_set("memory_limit", "1024M");

        # validate input file
        try {
            $request->validate([
                'data_gz' => 'required|file|max:40960', // max 40 MB
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'load_time' => now()->toDateTimeString(),
                'message' => 'Error during import: ' . $e->getMessage(),
                'ok' => false
            ], 422);
        }

        # is the file valid?
        $file = $request->file('data_gz');
        if (!$file || !$file->isValid()) {
            return response()->json([
                'load_time' => now()->toDateTimeString(),
                'message' => 'No valid file uploaded',
                'ok' => false
            ], 500);
        }

        # read and decompress the file
        $contents = file_get_contents($file->getRealPath());
        if ($contents === false) {
            return response()->json([
                'load_time' => now()->toDateTimeString(),
                'message' => 'Failed to read the uploaded file',
                'ok' => false
            ], 500);
        }

        # decode gzipped content
        $decompressed = gzdecode($contents);
        if ($decompressed === false) {
            return response()->json([
                'load_time' => now()->toDateTimeString(),
                'message' => 'Failed to decompress the uploaded file',
                'ok' => false
            ], 500);
        }

        # parse JSON
        $data = json_decode($decompressed, true);
        if ($data === null) {
            return response()->json([
                'load_time' => now()->toDateTimeString(),
                'message' => 'Failed to parse JSON from the uploaded file',
                'ok' => false
            ], 400);
        }

        DB::transaction(function () use ($data, $domainSk, $stats) {

            # get and save the domains
            $domainSk->saveDomains($data['domains'] ?? []);

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

            # Save market share and heatmap data
            $stats->parseAndSaveMarketShareData($data['calendar_heatmap_by_day'] ?? [], CalendarHeatmapByDay::class, self::CHUNK_SIZE);
            $stats->parseAndSaveMarketShareData($data['owner_market_share'] ?? [], OwnerMarketShare::class, self::CHUNK_SIZE);
            $stats->parseAndSaveMarketShareData($data['registrar_market_share'] ?? [], RegistrarMarketShare::class, self::CHUNK_SIZE);
            $stats->parseAndSaveMarketShareData($data['name_server_market_share'] ?? [], NameServerMarketShare::class, self::CHUNK_SIZE);
        });

        return response()->json([
            'load_time' => now()->toDateTimeString(),
            'imported_data' => $data['domains'][0], //"datata ratata",
            'type' => gettype($data['domains'][0]),
            'is_array' => is_array($data['domains'][0]),
            'is_object' => is_object($data['domains'][0]),
            'memory_usage' => memory_get_usage(true) / 1024 / 1024 . " MB",
            'memory_limit' => ini_get("memory_limit"),
            'ok' => true
        ], 201);
    }
}
