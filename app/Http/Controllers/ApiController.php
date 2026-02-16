<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\DomainStatistic;
use App\Models\Crawling;
use App\Models\CalendarHeatmapByDay;
use App\Models\OwnerMarketShare;
use App\Models\RegistrarMarketShare;
use App\Models\NameServerMarketShare;
use App\Services\DomainSk;
use App\Services\Stats;
use App\Services\ExpiringDomains;
use App\Services\Metrics;
use App\Services\TableRotation;
use Throwable;
use Exception;
use RuntimeException;

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

    public function import(Request $request, DomainSk $domainSk, Stats $stats, ExpiringDomains $expiringDomains, Metrics $metricService, TableRotation $tableRotation)
    {

        # set higher memory limit and execution time
        ini_set("memory_limit", "1024M");
        Log::info("API IMPORT: Memory limit set to " . ini_get("memory_limit"));

        # validate input file
        try {
            $request->validate([
                'data_gz' => 'required|file|max:40960', // max 40 MB
            ]);
        } catch (Exception $e) {
            Log::error("API IMPORT: File validation failed - " . $e->getMessage());
        }

        # is the file valid?
        $file = $request->file('data_gz');
        if (!$file || !$file->isValid()) {
            Log::error("API IMPORT: Uploaded file is not valid.");
        }

        # read and decompress the file
        $contents = file_get_contents($file->getRealPath());
        if ($contents === false) {
            Log::error("API IMPORT: Failed to read the uploaded file.");
        }

        # decode gzipped content
        $decompressed = gzdecode($contents);
        if ($decompressed === false) {
            Log::error("API IMPORT: Failed to decompress the uploaded file.");
        }

        # parse JSON
        $data = json_decode($decompressed, true);
        if ($data === null) {
            Log::error("API IMPORT: Failed to parse JSON from the uploaded file.");
        }

        # process and save data within a transaction
        try {
            $tablesSwapped = $tableRotation->swapTables();
            if (!$tablesSwapped) {
                Log::error("Tables not prepared for next step. Stopping process.");
                throw new RuntimeException("Tables not prepared for next step. Stopping process.");
            }

            DB::transaction(function () use ($data, $domainSk, $stats, $expiringDomains, $metricService) {

                # save the domains
                $domainSk->saveDomains($data['domains'] ?? []);

                # save expiring domains
                $expiringDomains->saveExpiringDomains($data['expiring_domains_next_days'] ?? []);

                # Create and save DomainStatistic
                $domainStatistic = new DomainStatistic([
                    'data_length_in_bytes' => $data['data_length_in_bytes'] ?? null,
                    'data_extraction_duration_in_seconds' => $data['data_extraction_duration_in_seconds'] ?? null,
                    'longest_domain_name' => $data['longest_domain_name'] ?? null,
                    'longest_domain_name_length' => $data['longest_domain_name_length'] ?? null,
                    'avg_domain_name_length' => $data['avg_domain_name_length'] ?? null,
                ]);

                # Process loads_info
                if (isset($data['loads_info'])) {

                    if (isset($data['loads_info']['latest_load'])) {
                        $crawlingPrevious = new Crawling($data['loads_info']['previous_load']);
                        $crawlingPrevious->save();
                    }

                    if (isset($data['loads_info']['previous_load'])) {
                        $crawlingLatest = new Crawling($data['loads_info']['latest_load']);
                        $crawlingLatest->save();
                    }

                    if (isset($data['loads_info']['number_of_domains_over_time'])) {
                        $metricService->saveMetric($data['loads_info']['number_of_domains_over_time'], 'number_of_domains');
                    }

                    if (isset($data['loads_info']['crawling_duration_over_time'])) {
                        $metricService->saveMetric($data['loads_info']['crawling_duration_over_time'], 'crawling_duration');
                    }

                    if (isset($data['loads_info']['download_speed_over_time'])) {
                        $metricService->saveMetric($data['loads_info']['download_speed_over_time'], 'download_speed');
                    }

                    if (isset($data['loads_info']['crawling_average_duration_seconds'])) {
                        $domainStatistic->crawling_average_duration_seconds = $data['loads_info']['crawling_average_duration_seconds'] ?? null;
                    }

                    if (isset($data['loads_info']['average_download_speed_bytes_per_second'])) {
                        $domainStatistic->average_download_speed_bytes_per_second = $data['loads_info']['average_download_speed_bytes_per_second'] ?? null;
                    }
                }

                $domainStatistic->save();

                # Save market share and heatmap data
                $stats->parseAndSaveMarketShareData($data['calendar_heatmap_by_day'] ?? [], CalendarHeatmapByDay::class, self::CHUNK_SIZE);
                $stats->parseAndSaveMarketShareData($data['owner_market_share'] ?? [], OwnerMarketShare::class, self::CHUNK_SIZE);
                $stats->parseAndSaveMarketShareData($data['registrar_market_share'] ?? [], RegistrarMarketShare::class, self::CHUNK_SIZE);
                $stats->parseAndSaveMarketShareData($data['name_server_market_share'] ?? [], NameServerMarketShare::class, self::CHUNK_SIZE);
            });
        } catch (Throwable $e) {
            Log::error("API IMPORT: Database transaction aborted: ", [
                'load_time' => now()->toDateTimeString(),
                'memory_usage_mb' => memory_get_usage(true) / 1024 / 1024,
                'memory_limit' => ini_get("memory_limit"),
                'type' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'sql' => $e instanceof QueryException ? $e->getSql() : null,
                'bindings' => $e instanceof QueryException ? $e->getBindings() : null,
                'stack_trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        Log::info("API IMPORT: Data import completed successfully.", [
            'load_time' => now()->toDateTimeString(),
            'imported_records' => count($data['domains'] ?? []),
            'type' => gettype($data['domains'][0]),
            'memory_usage_mb' => memory_get_usage(true) / 1024 / 1024,
            'memory_limit' => ini_get("memory_limit"),
        ]);
    }
}
