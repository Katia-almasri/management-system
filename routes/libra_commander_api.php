<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraController;

Route::group(['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers']], function () {
    Route::group(['middleware' => 'is-libra-commander-exist'], function () {
        Route::post('add-poultry-reciept-detection', [LibraController::class, 'addPoultryRecieptDetection']);
        Route::get('get-row-material-for-reciept', [LibraController::class, 'getRowMaterialForReciept']);
        Route::get('get-reciepts', [LibraController::class, 'getReciepts']);

        Route::group(['middleware' => 'check-reciept-id'], function () {
            Route::get('get-reciept-info/{recieptId}', [LibraController::class, 'getRecieptInfo']);
            Route::post('add-weight-after-arrival-detection/{recieptId}', [LibraController::class, 'addWeightAfterArrivalDetection'])->middleware('check-reciept-not-weighted');
            Route::get('get-weight-after-arrival-for-reciept/{recieptId}', [LibraController::class, 'getWeightAfterArrival'])->middleware('check-reciept-weighted');
    
        });
        
        
    });
});