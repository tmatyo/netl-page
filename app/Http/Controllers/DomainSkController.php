<?php

namespace App\Http\Controllers;

use Throwable;
use Exception;
use Illuminate\Http\Request;
use App\Models\CalendarHeatmapByDay;
use App\Models\Crawling;
use App\Models\Domain;
use App\Models\DomainStatistic;
use App\Models\OwnerMarketShare;
use App\Models\RegistrarMarketShare;
use App\Models\NameServerMarketShare;
use App\Models\Metric;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class DomainSkController extends Controller
{
    public function domainStatistics()
    {
        $crawlingInfo = null;
        $domainStatistics = null;
        $numberOfDomainsMetric = [];
        $crawlingDurationMetric = [];
        $downloadSpeedMetric = [];
        $calendarHeatmapByDay = [];
        $ownersMarketShare = [];
        $registrarsMarketShare = [];
        $nameserverMarketShare = [];

        try {
            $crawlingInfo = Crawling::orderBy('time_generated', 'asc')->take(2)->get();
            $domainStatistics = DomainStatistic::get()->first();
            $numberOfDomainsMetric = Metric::where('metric_type', 'number_of_domains')->orderBy('date', 'asc')->get(['value', 'date']);
            $crawlingDurationMetric = Metric::where('metric_type', 'crawling_duration')->orderBy('date', 'asc')->get(['value', 'date']);
            $downloadSpeedMetric = Metric::where('metric_type', 'download_speed')->orderBy('date', 'asc')->get(['value', 'date']);
            $calendarHeatmapByDay = CalendarHeatmapByDay::orderBy('expiry_day', 'asc')->take(365)->get(['expiry_day', 'domain_count']);
            $ownersMarketShare = OwnerMarketShare::query()->orderBy('domain_count', 'desc')->take(10)->get(['domain_count', 'owner']);
            $registrarsMarketShare = RegistrarMarketShare::query()->orderBy('domain_count', 'desc')->take(10)->get(['domain_count', 'registrar']);
            $nameserverMarketShare = NameServerMarketShare::query()->orderBy('count', 'desc')->take(10)->get(['count', 'ns']);
        } catch (Throwable $ex) {
            Log::error("HOME PAGE: Data query failed: ", ['exception' => $ex->getMessage()]);
        }

        return Inertia::render('Domenask/Index', [
            'latestCrawling' => $crawlingInfo[1] ?? [],
            'previousCrawling' => $crawlingInfo[0] ?? [],
            'domainStatistics' => $domainStatistics,
            'numberOfDomainsMetric' => $numberOfDomainsMetric,
            'crawlingDurationMetric' => $crawlingDurationMetric,
            'downloadSpeedMetric' => $downloadSpeedMetric,
            'calendarHeatmapByDay' => $calendarHeatmapByDay,
            'ownersMarketShare' => $ownersMarketShare,
            'registrarsMarketShare' => $registrarsMarketShare,
            'nameserverMarketShare' => $nameserverMarketShare,
        ]);
    }

    public function domainList(Request $request)
    {
        $domains = [];
        $error = null;
        $query = Domain::query();

        if ($request->filled('search')) {
            $query->where('domain', 'like', '%' . $request->search . '%');
        }

        try {
            $domains = $query->orderBy('id', 'asc')->paginate(20)->withQueryString();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return Inertia::render('Domenask/DomainList', [
            'domains' => $domains,
            'searchQuery' => $request->filled('search') ? $request->search : '',
            'error' => $error,
        ]);
    }

    public function ownersMarketshare(Request $request)
    {
        $owners = [];
        $error = null;
        $query = OwnerMarketShare::query();

        if ($request->filled('search')) {
            $query->where('owner', 'like', '%' . $request->search . '%');
        }

        try {
            $owners = $query->orderBy('domain_count', 'desc')->paginate(20)->withQueryString();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return Inertia::render('Domenask/OwnersMarketshare', [
            'ownersMarketShare' => $owners,
            'searchQuery' => $request->filled('search') ? $request->search : '',
            'error' => $error
        ]);
    }

    public function registrarsMarketshare(Request $request)
    {
        $registrars = [];
        $error = null;
        $query = RegistrarMarketShare::query();

        if ($request->filled('search')) {
            $query->where('registrar', 'like', '%' . $request->search . '%');
        }

        try {
            $registrars = $query->orderBy('domain_count', 'desc')->paginate(20)->withQueryString();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return Inertia::render('Domenask/RegistrarsMarketshare', [
            'registrarsMarketShare' => $registrars,
            'searchQuery' => $request->filled('search') ? $request->search : '',
            'error' => $error
        ]);
    }

    public function nameserverMarketshare(Request $request)
    {
        $nameservers = [];
        $error = null;
        $query = NameServerMarketShare::query();

        if ($request->filled('search')) {
            $query->where('ns', 'like', '%' . $request->search . '%');
        }

        try {
            $nameservers = $query->orderBy('count', 'desc')->paginate(20)->withQueryString();
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return Inertia::render('Domenask/NameserverMarketshare', [
            'nameserverMarketShare' => $nameservers,
            'searchQuery' => $request->filled('search') ? $request->search : '',
            'error' => $error
        ]);
    }
}
