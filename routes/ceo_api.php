<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CEOController;

Route::post('login',[CEOController::class, 'CEOLogin'])->name('CEOLogin');
Route::group( ['prefix' => 'ceo','middleware' => ['auth:managers-api'] ],function(){
   // authenticated staff routes here
    Route::get('logout',[CEOController::class, 'logout']);
    Route::get('ceck-dashboard',[CEOController::class, 'CEODashboard']);
    Route::get('ceck-ceo-role',[CEOController::class, 'checkCEORole']);

});

