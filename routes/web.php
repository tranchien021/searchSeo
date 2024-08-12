<?php

use App\Models\Keyword;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;

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


Route::get('/', [Controller::class, 'home'])->name('home.rankings');
Route::post('/post-rankings', [Controller::class, 'postRankings'])->name('post-rankings');
Route::get('/detail-rankings/{keyword}', [Controller::class, 'detailRankings'])->name('detail-rankings');

