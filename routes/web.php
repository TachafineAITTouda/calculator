<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalculatorController;

Route::get('/', function () {
    return view('welcome');
});

Route::controller(CalculatorController::class)->group(function () {
    Route::get('/calculator', 'index')->name('calculator');
    Route::post('/calculator', 'calculate')->name('calculate');
});
