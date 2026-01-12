<?php

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DomainSkController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\LocaleController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::post('/locale', [LocaleController::class, 'change'])->name('locale.change');

Route::get('/domainsk', [DomainSkController::class, 'domainStatistics'])->name('domainsk.statistics');
Route::get('/domainsk/domain-list', [DomainSkController::class, 'domainList'])->name('domainsk.domain-list');
Route::get('/domainsk/owners-marketshare', [DomainSkController::class, 'ownersMarketshare'])->name('domainsk.owners-marketshare');
Route::get('/domainsk/registrars-marketshare', [DomainSkController::class, 'registrarsMarketshare'])->name('domainsk.registrars-marketshare');
Route::get('/domainsk/nameserver-marketshare', [DomainSkController::class, 'nameserverMarketshare'])->name('domainsk.nameserver-marketshare');

Route::prefix('api')->withoutMiddleware([VerifyCsrfToken::class])->group(function () {
    Route::get('/', [ApiController::class, 'index'])->name('api.index');
    Route::post('/import', [ApiController::class, 'import'])->name('api.import');
});
