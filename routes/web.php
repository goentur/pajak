<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PajakController;
use App\Http\Controllers\PrediksiPajakController;
use App\Http\Controllers\RiwayatPajakController;
use Illuminate\Support\Facades\Route;

Route::get('/', [BerandaController::class, '__invoke'])->name('beranda');
Route::prefix('pajak')->name('pajak.')->controller(PajakController::class)->group(function () {
    Route::get('pbb', 'pbb')->name('pbb');
    Route::get('bphtb', 'bphtb')->name('bphtb');
    Route::get('pbjt', 'pbjt')->name('pbjt');
    Route::get('hotel', 'hotel')->name('hotel');
    Route::get('restoran', 'restoran')->name('restoran');
    Route::get('hiburan', 'hiburan')->name('hiburan');
    Route::get('ppj', 'ppj')->name('ppj');
    Route::get('parkir', 'parkir')->name('parkir');
    Route::get('reklame', 'reklame')->name('reklame');
    Route::get('air-tanah', 'airTanah')->name('air-tanah');
    Route::get('retribusi', 'retribusi')->name('retribusi');
});
Route::prefix('riwayat-pajak')->name('riwayat-pajak.')->controller(RiwayatPajakController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('data', 'data')->name('data');
});
Route::prefix('prediksi-pajak')->name('prediksi-pajak.')->controller(PrediksiPajakController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('data', 'data')->name('data');
});
