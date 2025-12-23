<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\CalendarHeatmapByDay;
use App\Models\Crawling;
use App\Models\Domain;
use App\Models\DomainStatistic;
use App\Models\OwnerMarketShare;
use App\Models\RegistrarMarketShare;
use App\Models\NameserverMarketShare;
use App\Models\Metric;
use Inertia\Inertia;

class DomainSkController extends Controller
{
    public function domainStatistics()
    {
        try {
            $crawlingInfo = Crawling::orderBy('time_generated', 'asc')->take(2)->get();
        } catch (Exception $e) {
            $crawlingInfo = null;
        }

        return Inertia::render('Domenask/Index', [
            'latestCrawling' => $crawlingInfo[1] ?? [],
            'previousCrawling' => $crawlingInfo[0] ?? [],
            'domainStatistics' => DomainStatistic::get()->first(),
            'numberOfDomainsMetric' => Metric::where('metric_type', 'number_of_domains')->orderBy('date', 'asc')->get(['value', 'date']),
            'crawlingDurationMetric' => Metric::where('metric_type', 'crawling_duration')->orderBy('date', 'asc')->get(['value', 'date']),
            'downloadSpeedMetric' => Metric::where('metric_type', 'download_speed')->orderBy('date', 'asc')->get(['value', 'date']),
            'calendarHeatmapByDay' => CalendarHeatmapByDay::orderBy('expiry_day', 'asc')->take(365)->get(['expiry_day', 'domain_count']),
            'ownersMarketShare' => OwnerMarketShare::query()->orderBy('domain_count', 'desc')->take(10)->get(['domain_count', 'owner']),
            'registrarsMarketShare' => RegistrarMarketShare::query()->orderBy('domain_count', 'desc')->take(10)->get(['domain_count', 'registrar']),
            'nameserverMarketShare' => NameserverMarketShare::query()->orderBy('count', 'desc')->take(10)->get(['count', 'ns']),
        ]);
    }

    public function domainList()
    {
        return Inertia::render('Domenask/DomainList', [
            'domains' => Domain::query()->paginate(20)
        ]);
    }

    public function ownersMarketshare()
    {
        return Inertia::render('Domenask/OwnersMarketshare', [
            'ownersMarketShare' => OwnerMarketShare::query()->orderBy('domain_count', 'desc')->paginate(20)
        ]);
    }

    public function registrarsMarketshare()
    {
        return Inertia::render('Domenask/RegistrarsMarketshare', [
            'registrarsMarketShare' => RegistrarMarketShare::query()->orderBy('domain_count', 'desc')->paginate(20)
        ]);
    }

    public function nameserverMarketshare()
    {
        return Inertia::render('Domenask/NameserverMarketshare', [
            'nameserverMarketShare' => NameserverMarketShare::query()->orderBy('count', 'desc')->paginate(20)
        ]);
    }
}
