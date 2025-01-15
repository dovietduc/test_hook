<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\ReconcileAcount;
use App\Models\User;
use Illuminate\Pipeline\Pipeline;
use App\Http\Controllers\CsvImportController;
use Illuminate\Support\Enumerable;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', function () {
    echo "test";
    echo 2;
    echo 788;
    echo 123;
//    $collection = collect([1, 2, 3, 4, 5]);
    $items = \Illuminate\Support\Facades\DB::table('plans')->get();

    dd($items);
//    return view('welcome');
});



Route::get('/import-csv', [CsvImportController::class, 'showForm'])->name('import.csv.show');
Route::post('/import-csv', [CsvImportController::class, 'import'])->name('import.csv.post');

Route::get('/test/notify', [CsvImportController::class, 'callExternalApi']);

