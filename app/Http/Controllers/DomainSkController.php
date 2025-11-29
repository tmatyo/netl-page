<?php

namespace App\Http\Controllers;

use App\Models\OwnerMarketShare;
use App\Models\RegistrarMarketShare;
use App\Models\NameserverMarketShare;
use Inertia\Inertia;

class DomainSkController extends Controller
{
    public function domainStatistics()
    {
        return Inertia::render('Domenask');
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
