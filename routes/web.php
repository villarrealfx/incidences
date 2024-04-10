<?php

use App\Http\Controllers\CircuitController;
use App\Http\Controllers\IncidenceController;
use App\Http\Controllers\ServiceCenterController;
use App\Http\Controllers\SubstationController;
use App\Models\Incidence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::name('incidences')->get('/incidences', [IncidenceController::class, 'index']);
Route::name('import')->post('/import', [IncidenceController::class, 'import']);
Route::name('export.general.ndi')->get('/export/general/ndi/{period_id}', [IncidenceController::class, 'exportGeneralNDI']);
Route::name('export.ndi')->get('/export/ndi/{cs}/{period}', [IncidenceController::class, 'exportNDI']);
Route::name('export.general.ndi.year')->get('/export/general/year/ndi/{year}', [IncidenceController::class, 'exportGeneralNDIYear']);
Route::name('export.ndi.year')->get('/export/year/ndi/{cs}/{year}', [IncidenceController::class, 'exportNDIYear']);
Route::name('export.manual-operations')->get('/export/manual-operations/{from}/{to}', [IncidenceController::class, 'exportManualOperations']);
Route::name('upload.file')->get('/upload', [IncidenceController::class, 'upload']);
Route::name('ndi')->get('/ndi', [IncidenceController::class, 'ndi']);
Route::name('ndi.year')->get('/ndi/year', [IncidenceController::class, 'ndi_year']);
Route::name('charts')->get('/charts', [IncidenceController::class, 'charts']);
Route::name('systems')->get('/systems', [IncidenceController::class, 'systems']);
Route::name('manual-operations')->get('/manual-operations', [IncidenceController::class, 'manual_operations']);
Route::name('causes')->get('/causes', [IncidenceController::class, 'causes']);
Route::name('periods')->get('/periods', [IncidenceController::class, 'periods']);

Route::name('service.centers')->get('/service/centers', [ServiceCenterController::class, 'index']);

Route::name('substations')->get('/substations', [SubstationController::class, 'index']);

Route::name('circuits')->get('/circuits', [CircuitController::class, 'index']);
Route::name('disconnectors')->get('/disconnectors', [CircuitController::class, 'disconnectors']);
Route::name('fuse-cutouts')->get('/fuse-cutouts', [CircuitController::class, 'fuseCutouts']);
Route::name('transformer-banks')->get('/transformer-banks', [CircuitController::class, 'banks']);
Route::name('distribution-transformers')->get('/distribution-transformers', [CircuitController::class, 'distributionTransformers']);
Route::name('pac-list')->get('/pac-list', [CircuitController::class, 'pacCircuits']);
Route::name('pac-list-xls')->get('/pac-list-xls', [CircuitController::class, 'exportPACCircuitsList']);
Route::name('pac-blocks')->get('/pac-blocks', [CircuitController::class, 'pacBlocks']);
Route::name('pac-blocks-xls')->get('/pac-blocks-xls/{day_blocks?}/{day_power?}/{night_blocks?}/{night_power?}/{exclude?}', [CircuitController::class, 'exportPACBlocks']);
Route::name('circuit-loads')->get('/circuit-loads', [CircuitController::class, 'circuitLoads']);

Route::name('fill-systems')->get('/fill-systems', [IncidenceController::class, 'fillSystemsData']);
Route::name('fill-subcauses')->get('/fill-subcauses', [IncidenceController::class, 'fillSubCausesData']);
