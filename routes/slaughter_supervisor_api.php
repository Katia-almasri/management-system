<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SlaughterSupervisorController;

Route::group( ['middleware' => ['auth:managers-api', 'check-scope-managers', 'scopes:managers'] ],function(){

    Route::group( ['middleware' => 'is-slaughter-supervisor'] ,function(){
        Route::get('display-input-slaughters',[SlaughterSupervisorController::class, 'displayInputSlaughters']);
        // Route::post('change-state-input',[SlaughterSupervisorController::class, 'changeStateInput']);
        Route::get('display-output-total-weight',[SlaughterSupervisorController::class, 'displayOutputDetTotalWeight']);
        Route::post('add-output-slaughters',[SlaughterSupervisorController::class, 'addOutputSlaughters']);
        // ->middleware('is-exist-type-id-input-slaughters');
        // Route::post('processing-is-done',[SlaughterSupervisorController::class, 'processing_is_done'])->middleware('is-exist-input-slaughters');
        Route::get('display-types-slaughter',[SlaughterSupervisorController::class, 'displayOutputTypesSlaughter']);
        Route::get('display-output-slaughter',[SlaughterSupervisorController::class, 'displayOutputSlaughter']);
        Route::post('command-directTo-bahra',[SlaughterSupervisorController::class, 'commandDirectToBahra'])
        ->middleware('is-exist-id-to-direct-bahra');



    });



});
