<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CuttingController;

Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-cutting-supervisor'] ,function(){

        Route::get('display-input-cutting',[CuttingController::class, 'displayInputCutting']);
        Route::post('cutting-is-done',[CuttingController::class, 'cutting_is_done'])
        ->middleware('is-exist-input-cutting');
        Route::get('display-total-input',[CuttingController::class, 'displayInputCuttingTotalWeight']);
        Route::post('add-output-cutting/{type_id}',[CuttingController::class, 'addOutputCutting'])
        ->middleware('is-exist-type-id-input-cutting');
        Route::get('display-output-cutting',[CuttingController::class, 'displayOutputCutting']);

    });



});
