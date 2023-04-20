<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountingController;
use App\Http\Controllers\TripController;

Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-accounting-manager'] ,function(){


        Route::get('display-sales',[AccountingController::class, 'displaySalesRequests']);
        Route::get('display-purchacing',[AccountingController::class, 'displayPurchacingRequests']);
        Route::get('display-trips',[TripController::class, 'displayTrip']);
    });



});
