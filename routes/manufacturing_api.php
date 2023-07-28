<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ManufacturingController;


Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-manufacturing-supervisor'] ,function(){
        Route::get('display-input-munufacturing',[ManufacturingController::class, 'displayInputManufacturing']);
        Route::post('munufacturing-is-done',[ManufacturingController::class, 'ManufacturingIsDone'])
        ->middleware('is-exist-input-munufacturing');
        Route::get('display-total-input-munufacturing',[ManufacturingController::class, 'displayInputManufacturingTotalWeight']);
        Route::post('add-output-munufacturing/{type_id}',[ManufacturingController::class, 'addOutputManufacturing'])
        ->middleware('is-exist-type-id-input-munufacturing');
        Route::get('display-output-munufacturing',[ManufacturingController::class, 'displayOutputManufacturing']);
        Route::get('display-output-type-munufacturing',[ManufacturingController::class, 'displayOutputTypeManufacturing']);
        Route::get('display-output-munufacturing-where',[ManufacturingController::class, 'displayManufacturingOutputWhereNotOutputable']);

        //////////////// DITRCT MANUFACTORING TO ////////////////
        Route::post('direct-manufactoring-to',[ManufacturingController::class, 'directManufactoringTo']);
        Route::get('output-remnat-manufacturing',[ManufacturingController::class, 'displayOutputRemnatmanufacturing']);


        /////////////////////////dashboard//////////////////////////////
        Route::get('count-type-production-manufacturing',[ManufacturingController::class, 'CountTypeProductionManufacturing']);
        Route::get('chart-input-manufacturing-this-month',[ManufacturingController::class, 'chartInputManufacturingThisMonth']);
        Route::get('chart-output-manufacturing-this-month',[ManufacturingController::class, 'chartOutputManufacturingThisMonth']);

        ///////////////////// NOTIFICATION PART //////////////
        Route::get('display-notification',[ManufacturingController::class, 'displayNotification']);
        Route::get('display-notification2',[ManufacturingController::class, 'displayNotification2']);
        
        
    });
});
