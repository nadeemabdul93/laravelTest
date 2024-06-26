<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/sort-csv', [App\Http\Controllers\CsvController::class,'sortCsv'])->name('sort-csv')->middleware('auth');
Route::get('/sort-csv', [App\Http\Controllers\CsvController::class,'uploadCsv'])->name('upload-csv')->middleware('auth');
Route::post('/files', [App\Http\Controllers\FileController::class,'upload'])->middleware('auth')->name('upload');

Route::get('/files', [App\Http\Controllers\FileController::class,'index'])->middleware('auth')->name('files');

Route::get('/files/{id}', [App\Http\Controllers\FileController::class,'download'])->middleware('auth')->name('download');

Route::get('/files/{file}/share', [App\Http\Controllers\FileController::class,'generateShareLink'])->name('share');

Route::post('/generate-link', [App\Http\Controllers\FileController::class, 'generateLink'])->name('files.generate-link');

Route::get('/share/{token}', [App\Http\Controllers\ShareLinkController::class,'handleShareLink']);
