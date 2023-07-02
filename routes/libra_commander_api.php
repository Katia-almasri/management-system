<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibraController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\ChartController;

Route::group(['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers']], function () {
    Route::group(['middleware' => 'is-libra-commander-exist'], function () {
        Route::post('add-poultry-reciept-detection/{trip_id}', [LibraController::class, 'addPoultryRecieptDetection']);
        Route::get('get-row-material-for-reciept', [LibraController::class, 'getRowMaterialForReciept']);
        Route::get('get-reciepts', [LibraController::class, 'getReciepts']);

        Route::group(['middleware' => 'check-reciept-id'], function () {
            Route::get('get-reciept-info/{recieptId}', [LibraController::class, 'getRecieptInfo']);
            Route::post('add-weight-after-arrival-detection/{recieptId}', [LibraController::class, 'addWeightAfterArrivalDetection'])->middleware('check-reciept-not-weighted');
            Route::get('get-weight-after-arrival-for-reciept/{recieptId}', [LibraController::class, 'getWeightAfterArrival']);
        });
        Route::get('display-trip-libra', [TripController::class, 'displayTripInLibra']);

        //dashboard
        Route::get('display-count-poultry', [ChartController::class, 'CountPoultryReceiptDetection']);
        Route::get('display-count-poultry-where-NotAfter', [ChartController::class, 'CountPoultryReceiptDetectionwhereNotAfter']);
        Route::get('display-count-trip', [ChartController::class, 'CountTripInRoad']);
        Route::get('display-chart-Poultry', [ChartController::class, 'ChartPoultryReceiptDetection']);




    });
});
