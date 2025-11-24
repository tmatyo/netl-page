<?php

namespace App\Http\Controllers;
use Inertia\Inertia;

class DomainSkController extends Controller
{
    public function domainStatistics()
    {
        return Inertia::render('Domenask');
    }
    public function domainList()
    {
        return Inertia::render('Domenask/DomainList');
    }
    public function domainOwners()
    {
        return Inertia::render('Domenask/DomainOwners');
    }
    public function domainRegistrars()
    {
        return Inertia::render('Domenask/DomainRegistrars');
    }
}
