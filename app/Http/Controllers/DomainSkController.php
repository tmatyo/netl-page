<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

class DomainSkController extends Controller
{
    public function domainStatistics()
    {
        return Inertia::render('Domenask');
    }
    public function OwnersMarketshare()
    {
        return Inertia::render('Domenask/OwnersMarketshare');
    }
    public function RegistrarsMarketshare()
    {
        return Inertia::render('Domenask/RegistrarsMarketshare');
    }
    public function NameserverMarketshare()
    {
        return Inertia::render('Domenask/NameserverMarketshare');
    }
}
