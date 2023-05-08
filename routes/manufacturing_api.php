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
    });
});
