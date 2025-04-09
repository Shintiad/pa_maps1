<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoPenyakitController;
use App\Http\Controllers\KasusPenyakitController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\MapsController;
use App\Http\Controllers\PendudukController;
use App\Http\Controllers\PenyakitController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\UnduhDataController;
use App\Http\Controllers\UserController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'showAllPenyakit']);
Route::get('/get-map-link', [HomeController::class, 'getMapLink'])->name('getMapLinkLanding');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'showAll'])->name('dashboard');

    Route::get('/info-penyakit', [InfoPenyakitController::class, 'showInfo'])->name('info-penyakit');
    Route::get('/info-penyakit/add/{id?}', [InfoPenyakitController::class, 'createInfo'])->name('add-info');
    Route::post('/info-penyakit/add', [InfoPenyakitController::class, 'storeInfo']);
    Route::get('/info-penyakit/{id}/edit', [InfoPenyakitController::class, 'editInfo'])->name('edit-info');
    Route::put('/info-penyakit/{id}', [InfoPenyakitController::class, 'updateInfo']);
    Route::delete('/info-penyakit/{id}/reset', [InfoPenyakitController::class, 'destroyInfo']);

    Route::get('/tahun', [TahunController::class, 'showTahun'])->name('tahun');
    Route::get('/tahun/add', [TahunController::class, 'create'])->name('add-tahun');
    Route::post('/tahun/add', [TahunController::class, 'store']);
    Route::get('/tahun/{id}/edit', [TahunController::class, 'edit']);
    Route::put('/tahun/{id}', [TahunController::class, 'update']);
    Route::delete('/tahun/{id}', [TahunController::class, 'destroy']);

    Route::get('/kecamatan', [KecamatanController::class, 'showKecamatan'])->name('kecamatan');
    Route::get('/kecamatan/add', [KecamatanController::class, 'create'])->name('add-kecamatan');
    Route::post('/kecamatan/add', [KecamatanController::class, 'store']);
    Route::get('/kecamatan/{id}/edit', [KecamatanController::class, 'edit']);
    Route::put('/kecamatan/{id}', [KecamatanController::class, 'update']);
    Route::delete('/kecamatan/{id}', [KecamatanController::class, 'destroy']);

    Route::get('/penduduk', [PendudukController::class, 'showpenduduk'])->name('penduduk');
    Route::get('/penduduk/add', [PendudukController::class, 'create'])->name('add-penduduk');
    Route::post('/penduduk/add', [PendudukController::class, 'store']);
    Route::get('/penduduk/{id}/edit', [PendudukController::class, 'edit']);
    Route::put('/penduduk/{id}', [PendudukController::class, 'update']);
    Route::delete('/penduduk/{id}', [PendudukController::class, 'destroy']);

    Route::get('/penyakit', [PenyakitController::class, 'showPenyakit'])->name('penyakit');
    Route::get('/penyakit/add', [PenyakitController::class, 'create'])->name('add-penyakit');
    Route::post('/penyakit/add', [PenyakitController::class, 'store']);
    Route::get('/penyakit/{id}/edit', [PenyakitController::class, 'edit']);
    Route::put('/penyakit/{id}', [PenyakitController::class, 'update']);
    Route::delete('/penyakit/{id}', [PenyakitController::class, 'destroy']);
    Route::get('/regenerate-trend/{penyakitId}', [PenyakitController::class, 'regenerateTrendForDisease']);

    Route::get('/kasus', [KasusPenyakitController::class, 'showkasus'])->name('kasus');
    Route::get('/kasus/add', [KasusPenyakitController::class, 'create'])->name('add-kasus');
    Route::post('/kasus/add', [KasusPenyakitController::class, 'store']);
    Route::get('/kasus/{id}/edit', [KasusPenyakitController::class, 'edit']);
    Route::put('/kasus/{id}', [KasusPenyakitController::class, 'update']);
    Route::delete('/kasus/{id}', [KasusPenyakitController::class, 'destroy']);

    Route::get('/user', [UserController::class, 'showUser'])->name('user');
    Route::get('/user/add', [UserController::class, 'create'])->name('add-user');
    Route::post('/user/add', [UserController::class, 'store']);
    Route::get('/user/{id}/edit', [UserController::class, 'edit']);
    Route::put('/user/{id}', [UserController::class, 'update']);
    Route::delete('/user/{id}', [UserController::class, 'destroy']);
    Route::post('/user/{id}/verify-email', [UserController::class, 'verifyEmail'])->name('verify-email');
    Route::get('/user/search', [UserController::class, 'search'])->name('user-search');

    Route::get('/maps-penduduk', [MapsController::class, 'showAllPenduduk'])->name('maps-penduduk');

    Route::get('/maps-penyakit', [MapsController::class, 'showAllPenyakit'])->name('maps-penyakit');
    Route::get('/maps-penyakit/get-link', [MapsController::class, 'getMapLink'])->name('getMapLink');

    Route::get('/regenerate-population/{tahunId}', [MapsController::class, 'regenerateMapForYear']);
    Route::get('/regenerate-disease/{tahunId}/{penyakitId}', [MapsController::class, 'regenerateMapForDisease']);

    Route::get('/unduh-data', [UnduhDataController::class, 'showData'])->name('unduh-data');
    Route::get('/export-excel', [UnduhDataController::class, 'exportExcel'])->name('export-excel');
    Route::get('/export-pdf', [UnduhDataController::class, 'exportPDF'])->name('export-pdf');

    Route::get('/about', function () {
        return view('pages.about');
    })->name('about');

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
});


// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
