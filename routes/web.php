<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DomainSkController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::prefix('domainsk')
    ->controller(DomainSkController::class)
    ->group(function () {
        Route::get('/', [DomainSkController::class, 'domainStatistics'])->name('domainsk.statistics');
        Route::get('/domain-list', [DomainSkController::class, 'domainList'])->name('domainsk.domain-list');
        Route::get('/domain-owners', [DomainSkController::class, 'domainOwners'])->name('domainsk.domain-owners');
        Route::get('/domain-registrars', [DomainSkController::class, 'domainRegistrars'])->name('domainsk.domain-registrars');
    });
