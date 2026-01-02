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
        $domains = [];
        $error = null;

        try {
            $domains = Domain::orderBy('id', 'desc')->paginate(20);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return Inertia::render('Domenask/DomainList', [
            'domains' => $domains,
            'error' => $error,
        ]);
    }

    public function ownersMarketshare()
    {
        $owners = [];
        $error = null;

        try {
            $owners = OwnerMarketShare::query()->orderBy('domain_count', 'desc')->paginate(20);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return Inertia::render('Domenask/OwnersMarketshare', [
            'ownersMarketShare' => $owners,
            'error' => $error
        ]);
    }

    public function registrarsMarketshare()
    {
        $registrars = [];
        $error = null;

        try {
            $registrars = RegistrarMarketShare::query()->orderBy('domain_count', 'desc')->paginate(20);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return Inertia::render('Domenask/RegistrarsMarketshare', [
            'registrarsMarketShare' => $registrars,
            'error' => $error
        ]);
    }

    public function nameserverMarketshare()
    {
        $nameservers = [];
        $error = null;

        try {
            $nameservers = NameserverMarketShare::query()->orderBy('count', 'desc')->paginate(20);
        } catch (Exception $e) {
            $error = $e->getMessage();
        }

        return Inertia::render('Domenask/NameserverMarketshare', [
            'nameserverMarketShare' => $nameservers,
            'error' => $error
        ]);
    }
}
